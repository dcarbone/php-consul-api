<?php

namespace DCarbone\PHPConsulAPITests\Unit;

use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\Consul;
use DCarbone\PHPConsulAPI\HttpAuth;
use DCarbone\PHPConsulAPI\PHPLib\Request;
use DCarbone\PHPConsulAPITests\ConsulEnvTrait;
use GuzzleHttp\Client;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Tests for Config::merge(), the equivalent of the Go client's NewClient() config handling.
 *
 * @internal
 */
final class ConfigMergeTest extends TestCase
{
    use ConsulEnvTrait;

    /** @var string[] */
    private array $tmpFiles = [];

    protected function setUp(): void
    {
        $this->isolateConsulEnv();
    }

    protected function tearDown(): void
    {
        $this->restoreConsulEnv();
        foreach ($this->tmpFiles as $f) {
            if (is_file($f)) {
                chmod($f, 0600);
                unlink($f);
            }
        }
        $this->tmpFiles = [];
    }

    private function tokenFile(string $contents): string
    {
        $f = tempnam(sys_get_temp_dir(), 'consul-token-');
        self::assertIsString($f);
        file_put_contents($f, $contents);
        $this->tmpFiles[] = $f;
        return $f;
    }

    public function testMergeNullReturnsDefault(): void
    {
        $c = Config::merge(null);
        self::assertSame(Config::DEFAULT_ADDRESS, $c->Address);
        self::assertSame(Config::DEFAULT_SCHEME, $c->Scheme);
        self::assertSame('', $c->PathPrefix);
        self::assertSame('', $c->Token);
    }

    public function testMergeDoesNotMutateInput(): void
    {
        $this->setEnv(name: Consul::HTTPNamespaceEnvName, value: 'env-ns');
        $in = new Config(Address: 'https://proxy.example.com/consul', Namespace: '');
        $out = Config::merge($in);

        self::assertNotSame($in, $out);
        self::assertSame('https://proxy.example.com/consul', $in->Address);
        self::assertSame('http', $in->Scheme);
        self::assertSame('', $in->PathPrefix);
        self::assertSame('', $in->Namespace);

        self::assertSame('proxy.example.com', $out->Address);
        self::assertSame('https', $out->Scheme);
        self::assertSame('/consul', $out->PathPrefix);
        self::assertSame('env-ns', $out->Namespace);
    }

    public function testMergeDeepClonesHttpAuth(): void
    {
        $in = new Config(HttpAuth: new HttpAuth(username: 'u', password: 'p'));
        $out = Config::merge($in);
        self::assertNotSame($in->HttpAuth, $out->HttpAuth);
        self::assertEquals($in->HttpAuth, $out->HttpAuth);
    }

    public function testMergeFillsEmptyFieldsFromEnvDefaults(): void
    {
        $this->setEnv(name: Consul::HTTPAddrEnvName, value: '10.1.1.1:8500');
        $this->setEnv(name: Consul::HTTPCAFileEnvName, value: '/env/ca.pem');
        $this->setEnv(name: Consul::HTTPCAPathEnvName, value: '/env/ca.d');
        $this->setEnv(name: Consul::HTTPClientCertEnvName, value: '/env/cert.pem');
        $this->setEnv(name: Consul::HTTPClientKeyEnvName, value: '/env/key.pem');
        $this->setEnv(name: Consul::HTTPTLSServerNameEnvName, value: 'env.consul');
        $this->setEnv(name: Consul::HTTPNamespaceEnvName, value: 'env-ns');
        $this->setEnv(name: Consul::HTTPPartitionEnvName, value: 'env-part');

        $in = new Config(
            Address: '',
            Scheme: '',
            Namespace: '',
            CAFile: '',
            CertFile: '',
            KeyFile: '',
            Partition: '',
            CAPath: '',
            TLSServerName: '',
        );
        $in->Scheme = '';
        $in->JSONEncodeOpts = 0;
        $in->JSONDecodeMaxDepth = 0;

        $out = Config::merge($in);
        self::assertSame('10.1.1.1:8500', $out->Address);
        self::assertSame('http', $out->Scheme);
        self::assertSame('/env/ca.pem', $out->CAFile);
        self::assertSame('/env/ca.d', $out->CAPath);
        self::assertSame('/env/cert.pem', $out->CertFile);
        self::assertSame('/env/key.pem', $out->KeyFile);
        self::assertSame('env.consul', $out->TLSServerName);
        self::assertSame('env-ns', $out->Namespace);
        self::assertSame('env-part', $out->Partition);
        self::assertSame(JSON_UNESCAPED_SLASHES, $out->JSONEncodeOpts);
        self::assertSame(512, $out->JSONDecodeMaxDepth);
    }

    public function testMergeKeepsExplicitValuesOverEnv(): void
    {
        $this->setEnv(name: Consul::HTTPAddrEnvName, value: '10.1.1.1:8500');
        $this->setEnv(name: Consul::HTTPNamespaceEnvName, value: 'env-ns');
        $this->setEnv(name: Consul::HTTPPartitionEnvName, value: 'env-part');
        $this->setEnv(name: Consul::HTTPCAPathEnvName, value: '/env/ca.d');

        $out = Config::merge(new Config(Address: '10.2.2.2:8500', Namespace: 'ns', Partition: 'part', CAPath: '/ca.d'));
        self::assertSame('10.2.2.2:8500', $out->Address);
        self::assertSame('ns', $out->Namespace);
        self::assertSame('part', $out->Partition);
        self::assertSame('/ca.d', $out->CAPath);
    }

    public function testMergeDoesNotFillHttpAuthDatacenterOrWaitTime(): void
    {
        $this->setEnv(name: Consul::HTTPAuthEnvName, value: 'envuser:envpass');
        $in = new Config();
        $in->setHttpAuth(null);
        $out = Config::merge($in);
        self::assertNull($out->HttpAuth);
        self::assertSame('', $out->Datacenter);
        self::assertSame(0.0, $out->getWaitTime()->Seconds());
    }

    public function testMergeInsecureSkipVerifyIsORedWithEnv(): void
    {
        $this->setEnv(name: Consul::HTTPSSLVerifyEnvName, value: 'false');
        self::assertTrue(Config::merge(new Config(InsecureSkipVerify: false))->InsecureSkipVerify);
        self::assertTrue(Config::merge(new Config(InsecureSkipVerify: true))->InsecureSkipVerify);

        $this->setEnv(name: Consul::HTTPSSLVerifyEnvName, value: 'true');
        self::assertFalse(Config::merge(new Config(InsecureSkipVerify: false))->InsecureSkipVerify);
        self::assertTrue(Config::merge(new Config(InsecureSkipVerify: true))->InsecureSkipVerify);
    }

    public function testMergeValidatesCertAndKeyAfterFill(): void
    {
        $in = new Config();
        $in->CertFile = '/cert.pem';
        $this->expectException(\InvalidArgumentException::class);
        Config::merge($in);
    }

    public function testMergeEnvCertCombinedWithExplicitKey(): void
    {
        $this->setEnv(name: Consul::HTTPClientCertEnvName, value: '/env/cert.pem');
        $this->setEnv(name: Consul::HTTPClientKeyEnvName, value: '/env/key.pem');
        $out = Config::merge(new Config(CertFile: '/cert.pem', KeyFile: '/key.pem'));
        self::assertSame('/cert.pem', $out->CertFile);
        self::assertSame('/key.pem', $out->KeyFile);
    }

    /**
     * Port of the Go client's TestAPI_PrefixPath.
     *
     * @return array<string,array{string,string,string,string}>
     */
    public static function prefixPathCases(): array
    {
        return [
            'with http and prefix' => ['http://reverse.proxy.com/consul/path/prefix', 'reverse.proxy.com', '/consul/path/prefix', 'http'],
            'with https and prefix' => ['https://reverse.proxy.com/consul/path/prefix', 'reverse.proxy.com', '/consul/path/prefix', 'https'],
            'with http and no prefix' => ['http://localhost', 'localhost', '', 'http'],
            'with https and no prefix' => ['https://localhost', 'localhost', '', 'https'],
            'no scheme and no prefix' => ['localhost', 'localhost', '', 'http'],
            'with port and prefix' => ['http://10.0.0.1:8500/consul', '10.0.0.1:8500', '/consul', 'http'],
        ];
    }

    #[DataProvider('prefixPathCases')]
    public function testMergePrefixPath(string $addr, string $expectAddr, string $expectPrefix, string $expectScheme): void
    {
        $out = Config::merge(new Config(Address: $addr));
        self::assertSame($expectAddr, $out->Address);
        self::assertSame($expectPrefix, $out->PathPrefix);
        self::assertSame($expectScheme, $out->Scheme);
    }

    public function testMergeHTTPPrefixDoesNotRevertExplicitHTTPS(): void
    {
        $out = Config::merge(new Config(Address: 'http://localhost:8500', Scheme: 'https'));
        self::assertSame('https', $out->Scheme);
        self::assertSame('localhost:8500', $out->Address);
    }

    public function testMergeAddressFromEnvIsParsed(): void
    {
        $this->setEnv(name: Consul::HTTPAddrEnvName, value: 'https://env.example.com:8501/prefix');
        $out = Config::merge(null);
        self::assertSame('env.example.com:8501', $out->Address);
        self::assertSame('https', $out->Scheme);
        self::assertSame('/prefix', $out->PathPrefix);
    }

    public function testMergeUnknownSchemeThrows(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown protocol scheme: ftp');
        Config::merge(new Config(Address: 'ftp://localhost:8500'));
    }

    public function testMergeUnixSocket(): void
    {
        $original = new Client();
        $out = Config::merge(new Config(Address: 'unix:///var/run/consul.sock', HttpClient: $original));
        self::assertSame('/var/run/consul.sock', $out->Address);
        self::assertSame('', $out->PathPrefix);
        self::assertSame('http', $out->Scheme);
        self::assertNotSame($original, $out->HttpClient);
        self::assertInstanceOf(Client::class, $out->HttpClient);
        $curl = $out->HttpClient->getConfig('curl');
        self::assertIsArray($curl);
        self::assertSame('/var/run/consul.sock', $curl[\CURLOPT_UNIX_SOCKET_PATH]);

        $req = new Request('GET', 'v1/agent/self', $out, null);
        self::assertSame('http://localhost/v1/agent/self', (string)$req->getUri());
    }

    public function testRequestUriIncludesPathPrefix(): void
    {
        $out = Config::merge(new Config(Address: 'https://proxy.example.com/consul/api'));
        $req = new Request('GET', 'v1/kv/foo', $out, null);
        self::assertSame('/consul/api', $req->getPathPrefix());
        self::assertSame('https://proxy.example.com/consul/api/v1/kv/foo', (string)$req->getUri());
    }

    public function testRequestIncludesNamespaceAndPartitionParams(): void
    {
        $out = Config::merge(new Config(Datacenter: 'dc1', Namespace: 'ns1', Partition: 'part1'));
        $req = new Request('GET', 'v1/kv/foo', $out, null);
        self::assertSame('http://localhost:8500/v1/kv/foo?dc=dc1&ns=ns1&partition=part1', (string)$req->getUri());
    }

    public function testRequestOmitsEmptyPartition(): void
    {
        $out = Config::merge(new Config());
        $req = new Request('GET', 'v1/kv/foo', $out, null);
        self::assertSame('http://localhost:8500/v1/kv/foo', (string)$req->getUri());
    }

    public function testTokenEnvOnly(): void
    {
        $this->setEnv(name: Consul::HTTPTokenEnvName, value: 'env-token');
        $out = Config::merge(new Config());
        self::assertSame('env-token', $out->Token);
        self::assertSame('', $out->TokenFile);
    }

    public function testTokenFileEnvBeatsTokenEnv(): void
    {
        $file = $this->tokenFile("  env-file-token\n");
        $this->setEnv(name: Consul::HTTPTokenEnvName, value: 'env-token');
        $this->setEnv(name: Consul::HTTPTokenFileEnvName, value: $file);
        $out = Config::merge(new Config());
        self::assertSame('env-file-token', $out->Token);
        self::assertSame($file, $out->TokenFile);
    }

    public function testExplicitTokenBeatsTokenFileEnv(): void
    {
        $file = $this->tokenFile('env-file-token');
        $this->setEnv(name: Consul::HTTPTokenFileEnvName, value: $file);
        $out = Config::merge(new Config(Token: 'explicit-token'));
        self::assertSame('explicit-token', $out->Token);
    }

    public function testExplicitTokenFileBeatsExplicitToken(): void
    {
        $file = $this->tokenFile("explicit-file-token\n");
        $envFile = $this->tokenFile('env-file-token');
        $this->setEnv(name: Consul::HTTPTokenEnvName, value: 'env-token');
        $this->setEnv(name: Consul::HTTPTokenFileEnvName, value: $envFile);
        $out = Config::merge(new Config(Token: 'explicit-token', TokenFile: $file));
        self::assertSame('explicit-file-token', $out->Token);
        self::assertSame($file, $out->TokenFile);
    }

    public function testExplicitTokenFileViaSetter(): void
    {
        $file = $this->tokenFile('setter-file-token');
        $in = new Config();
        $in->setTokenFile($file);
        self::assertSame('setter-file-token', Config::merge($in)->Token);
    }

    public function testEmptyTokenFileContentsKeepsExistingToken(): void
    {
        $file = $this->tokenFile("   \n");
        $out = Config::merge(new Config(Token: 'explicit-token', TokenFile: $file));
        self::assertSame('explicit-token', $out->Token);
    }

    public function testMissingExplicitTokenFileThrows(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Error loading token file /does/not/exist');
        Config::merge(new Config(TokenFile: '/does/not/exist'));
    }

    public function testMissingEnvTokenFileThrows(): void
    {
        $this->setEnv(name: Consul::HTTPTokenFileEnvName, value: '/does/not/exist');
        $this->expectException(\RuntimeException::class);
        Config::merge(null);
    }

    public function testExplicitTokenSameAsEnvTokenFallsThroughToTokenFileEnv(): void
    {
        // Mirrors Go: an explicit token equal to the env token is indistinguishable from the default.
        $file = $this->tokenFile('env-file-token');
        $this->setEnv(name: Consul::HTTPTokenEnvName, value: 'same');
        $this->setEnv(name: Consul::HTTPTokenFileEnvName, value: $file);
        $out = Config::merge(new Config(Token: 'same'));
        self::assertSame('env-file-token', $out->Token);
    }

    public function testConsulConstructorUsesMergedConfig(): void
    {
        $file = $this->tokenFile('consul-file-token');
        $this->setEnv(name: Consul::HTTPTokenFileEnvName, value: $file);
        $in = new Config(Address: 'https://proxy.example.com/consul', Partition: 'p1');
        $consul = new Consul(config: $in);
        $cfg = $consul->KV->getConfig();
        self::assertSame('consul-file-token', $cfg->Token);
        self::assertSame('proxy.example.com', $cfg->Address);
        self::assertSame('/consul', $cfg->PathPrefix);
        self::assertSame('https', $cfg->Scheme);
        self::assertSame('p1', $cfg->Partition);
        self::assertSame('https://proxy.example.com/consul', $in->Address);
    }
}
