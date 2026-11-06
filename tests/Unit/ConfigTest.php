<?php

namespace DCarbone\PHPConsulAPITests\Unit;

use DCarbone\PHPConsulAPI\Consul;
use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\HttpAuth;
use DCarbone\PHPConsulAPITests\ConsulEnvTrait;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ConfigTest extends TestCase
{
    use ConsulEnvTrait;

    protected function setUp(): void
    {
        $this->isolateConsulEnv();
    }

    protected function tearDown(): void
    {
        $this->restoreConsulEnv();
    }

    public function testConstructorDefaults(): void
    {
        $c = new Config();
        self::assertSame('localhost:8500', Config::DEFAULT_ADDRESS);
        self::assertSame(Config::DEFAULT_ADDRESS, $c->getAddress());
        self::assertSame(Config::DEFAULT_ADDRESS, $c->Address);
        self::assertSame(Config::DEFAULT_SCHEME, $c->getScheme());
        self::assertSame(Config::DEFAULT_SCHEME, $c->Scheme);
        self::assertSame('', $c->getPathPrefix());
        self::assertSame('', $c->PathPrefix);
        self::assertSame('', $c->getDatacenter());
        self::assertSame('', $c->Datacenter);
        self::assertSame('', $c->getNamespace());
        self::assertSame('', $c->Namespace);
        self::assertSame('', $c->getPartition());
        self::assertSame('', $c->Partition);
        self::assertNull($c->HttpAuth);
        self::assertSame(0.0, $c->getWaitTime()->Seconds());
        self::assertSame('', $c->getToken());
        self::assertSame('', $c->Token);
        self::assertSame('', $c->getTokenFile());
        self::assertSame('', $c->TokenFile);
        self::assertSame('', $c->getTLSServerName());
        self::assertSame('', $c->TLSServerName);
        self::assertSame('', $c->getCAFile());
        self::assertSame('', $c->CAFile);
        self::assertSame('', $c->getCAPath());
        self::assertSame('', $c->CAPath);
        self::assertSame('', $c->getCertFile());
        self::assertSame('', $c->CertFile);
        self::assertSame('', $c->getKeyFile());
        self::assertSame('', $c->KeyFile);
        self::assertFalse($c->isInsecureSkipVerify());
        self::assertFalse($c->InsecureSkipVerify);
        self::assertInstanceOf(ClientInterface::class, $c->getHttpClient());
        self::assertSame(JSON_UNESCAPED_SLASHES, $c->getJSONEncodeOpts());
        self::assertSame(512, $c->getJSONDecodeMaxDepth());
        self::assertSame(0, $c->getJSONDecodeOpts());
    }

    public function testConstructorWithValues(): void
    {
        $httpClient = new Client();
        $c = new Config(
            Address: '10.0.0.1:8500',
            Scheme: 'https',
            Datacenter: 'dc1',
            Namespace: 'ns1',
            HttpAuth: new HttpAuth(username: 'admin', password: 'pass'),
            WaitTime: '30s',
            Token: 'my-token',
            TokenFile: '/tmp/token',
            CAFile: '/ca.pem',
            CertFile: '/cert.pem',
            KeyFile: '/key.pem',
            InsecureSkipVerify: true,
            HttpClient: $httpClient,
            JSONEncodeOpts: 0,
            JSONDecodeMaxDepth: 256,
            JSONDecodeOpts: JSON_BIGINT_AS_STRING,
            Partition: 'part1',
            PathPrefix: '/prefix',
            CAPath: '/certs',
            TLSServerName: 'consul.test',
        );
        self::assertSame('10.0.0.1:8500', $c->getAddress());
        self::assertSame('10.0.0.1:8500', $c->Address);
        self::assertSame('https', $c->getScheme());
        self::assertSame('https', $c->Scheme);
        self::assertSame('dc1', $c->getDatacenter());
        self::assertSame('dc1', $c->Datacenter);
        self::assertSame('ns1', $c->getNamespace());
        self::assertSame('ns1', $c->Namespace);
        self::assertSame('part1', $c->getPartition());
        self::assertSame('part1', $c->Partition);
        self::assertSame('/prefix', $c->getPathPrefix());
        self::assertSame('/prefix', $c->PathPrefix);
        self::assertInstanceOf(HttpAuth::class, $c->HttpAuth);
        self::assertSame('admin', $c->HttpAuth->getUsername());
        self::assertSame('pass', $c->HttpAuth->getPassword());
        self::assertSame(30.0, $c->getWaitTime()->Seconds());
        self::assertSame('my-token', $c->getToken());
        self::assertSame('my-token', $c->Token);
        self::assertSame('/tmp/token', $c->getTokenFile());
        self::assertSame('/tmp/token', $c->TokenFile);
        self::assertSame('/ca.pem', $c->getCAFile());
        self::assertSame('/ca.pem', $c->CAFile);
        self::assertSame('/certs', $c->getCAPath());
        self::assertSame('/certs', $c->CAPath);
        self::assertSame('/cert.pem', $c->getCertFile());
        self::assertSame('/cert.pem', $c->CertFile);
        self::assertSame('/key.pem', $c->getKeyFile());
        self::assertSame('/key.pem', $c->KeyFile);
        self::assertSame('consul.test', $c->getTLSServerName());
        self::assertSame('consul.test', $c->TLSServerName);
        self::assertTrue($c->isInsecureSkipVerify());
        self::assertTrue($c->InsecureSkipVerify);
        self::assertSame($httpClient, $c->getHttpClient());
        self::assertSame(0, $c->getJSONEncodeOpts());
        self::assertSame(256, $c->getJSONDecodeMaxDepth());
        self::assertSame(JSON_BIGINT_AS_STRING, $c->getJSONDecodeOpts());
    }

    public function testFluentSetters(): void
    {
        $c = new Config();
        $httpClient = new Client();

        $result = $c
            ->setAddress('192.168.1.1:8500')
            ->setScheme('https')
            ->setPathPrefix('/consul')
            ->setDatacenter('dc2')
            ->setNamespace('ns2')
            ->setPartition('part2')
            ->setWaitTime('15s')
            ->setToken('tok')
            ->setTokenFile('/tok.file')
            ->setTLSServerName('server.consul')
            ->setCAFile('/ca.pem')
            ->setCAPath('/ca.d')
            ->setCertFile('/cert.pem')
            ->setKeyFile('/key.pem')
            ->setInsecureSkipVerify(true)
            ->setHttpClient($httpClient)
            ->setHttpAuth(new HttpAuth(username: 'u', password: 'p'))
            ->setJSONEncodeOpts(JSON_PRETTY_PRINT)
            ->setJSONDecodeMaxDepth(128)
            ->setJSONDecodeOpts(JSON_BIGINT_AS_STRING);

        self::assertSame($c, $result);
        self::assertSame('192.168.1.1:8500', $c->getAddress());
        self::assertSame('192.168.1.1:8500', $c->Address);
        self::assertSame('https', $c->getScheme());
        self::assertSame('https', $c->Scheme);
        self::assertSame('/consul', $c->getPathPrefix());
        self::assertSame('/consul', $c->PathPrefix);
        self::assertSame('dc2', $c->getDatacenter());
        self::assertSame('dc2', $c->Datacenter);
        self::assertSame('ns2', $c->getNamespace());
        self::assertSame('ns2', $c->Namespace);
        self::assertSame('part2', $c->getPartition());
        self::assertSame('part2', $c->Partition);
        self::assertSame(15.0, $c->getWaitTime()->Seconds());
        self::assertSame('tok', $c->getToken());
        self::assertSame('tok', $c->Token);
        self::assertSame('/tok.file', $c->getTokenFile());
        self::assertSame('/tok.file', $c->TokenFile);
        self::assertSame('server.consul', $c->getTLSServerName());
        self::assertSame('server.consul', $c->TLSServerName);
        self::assertSame('/ca.pem', $c->getCAFile());
        self::assertSame('/ca.pem', $c->CAFile);
        self::assertSame('/ca.d', $c->getCAPath());
        self::assertSame('/ca.d', $c->CAPath);
        self::assertSame('/cert.pem', $c->getCertFile());
        self::assertSame('/cert.pem', $c->CertFile);
        self::assertSame('/key.pem', $c->getKeyFile());
        self::assertSame('/key.pem', $c->KeyFile);
        self::assertTrue($c->isInsecureSkipVerify());
        self::assertSame($httpClient, $c->getHttpClient());
        self::assertInstanceOf(HttpAuth::class, $c->HttpAuth);
        self::assertSame('u', $c->HttpAuth->getUsername());
        self::assertSame('p', $c->HttpAuth->getPassword());
        self::assertSame(JSON_PRETTY_PRINT, $c->getJSONEncodeOpts());
        self::assertSame(JSON_PRETTY_PRINT, $c->JSONEncodeOpts);
        self::assertSame(128, $c->getJSONDecodeMaxDepth());
        self::assertSame(128, $c->JSONDecodeMaxDepth);
        self::assertSame(JSON_BIGINT_AS_STRING, $c->getJSONDecodeOpts());
        self::assertSame(JSON_BIGINT_AS_STRING, $c->JSONDecodeOpts);
    }

    public function testSetSchemeWithBoolTrue(): void
    {
        $c = new Config();
        $c->setScheme(true);
        self::assertSame('https', $c->getScheme());
    }

    public function testSetSchemeWithBoolFalse(): void
    {
        $c = new Config();
        $c->setScheme(false);
        self::assertSame('http', $c->getScheme());
    }

    public function testSetSchemeWithStringTrue(): void
    {
        $c = new Config();
        $c->setScheme('true');
        self::assertSame('https', $c->getScheme());
    }

    public function testSetSchemeWithStringFalse(): void
    {
        $c = new Config();
        $c->setScheme('false');
        self::assertSame('http', $c->getScheme());
    }

    public function testSetHttpAuthWithString(): void
    {
        $c = new Config();
        $c->setHttpAuth('admin:secret');
        self::assertInstanceOf(HttpAuth::class, $c->HttpAuth);
        self::assertSame('admin', $c->HttpAuth->getUsername());
        self::assertSame('secret', $c->HttpAuth->getPassword());
    }

    public function testSetHttpAuthWithStringNoPassword(): void
    {
        $c = new Config();
        $c->setHttpAuth('admin');
        self::assertInstanceOf(HttpAuth::class, $c->HttpAuth);
        self::assertSame('admin', $c->HttpAuth->getUsername());
    }

    public function testSetHttpAuthWithNull(): void
    {
        $c = new Config(HttpAuth: new HttpAuth(username: 'user'));
        self::assertNotNull($c->HttpAuth);

        $c->setHttpAuth(null);
        self::assertNull($c->HttpAuth);
    }

    public function testSetWaitTimeWithNull(): void
    {
        $c = new Config(WaitTime: '10s');
        self::assertSame(10.0, $c->getWaitTime()->Seconds());

        $c->setWaitTime(null);
        self::assertSame(0.0, $c->getWaitTime()->Seconds());
    }

    public function testCloneDeepCopiesHttpAuth(): void
    {
        $c = new Config(HttpAuth: new HttpAuth(username: 'user', password: 'pass'));
        $clone = clone $c;
        self::assertNotSame($c->HttpAuth, $clone->HttpAuth);
        self::assertEquals($c->HttpAuth, $clone->HttpAuth);
    }

    public function testCertAndKeyMustBothBeSetOrEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Config(CertFile: '/cert.pem', KeyFile: '');
    }

    public function testCertAndKeyMustBothBeSetOrEmptyReverse(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Config(CertFile: '', KeyFile: '/key.pem');
    }

    public function testCertFromEnvWithoutKeyThrows(): void
    {
        $this->setEnv(name: Consul::HTTPClientCertEnvName, value: '/env/cert.pem');
        $this->expectException(\InvalidArgumentException::class);
        new Config();
    }

    public function testNewDefaultConfig(): void
    {
        $c = Config::newDefaultConfig();
        self::assertInstanceOf(Config::class, $c);
        self::assertSame(Config::DEFAULT_ADDRESS, $c->getAddress());
        self::assertSame(Config::DEFAULT_SCHEME, $c->getScheme());
    }

    /**
     * Port of the Go client's TestAPI_DefaultConfig_env.
     */
    public function testDefaultConfigFromEnv(): void
    {
        $this->setEnv(name: Consul::HTTPAddrEnvName, value: '1.2.3.4:5678');
        $this->setEnv(name: Consul::HTTPTokenEnvName, value: 'abcd1234');
        $this->setEnv(name: Consul::HTTPAuthEnvName, value: 'username:password');
        $this->setEnv(name: Consul::HTTPSSLEnvName, value: '1');
        $this->setEnv(name: Consul::HTTPCAFileEnvName, value: 'ca.pem');
        $this->setEnv(name: Consul::HTTPCAPathEnvName, value: 'certs/');
        $this->setEnv(name: Consul::HTTPClientCertEnvName, value: 'client.crt');
        $this->setEnv(name: Consul::HTTPClientKeyEnvName, value: 'client.key');
        $this->setEnv(name: Consul::HTTPTLSServerNameEnvName, value: 'consul.test');
        $this->setEnv(name: Consul::HTTPSSLVerifyEnvName, value: '0');

        $c = Config::newDefaultConfig();
        self::assertSame('1.2.3.4:5678', $c->Address);
        self::assertSame('abcd1234', $c->Token);
        self::assertNotNull($c->HttpAuth);
        self::assertSame('username', $c->HttpAuth->getUsername());
        self::assertSame('password', $c->HttpAuth->getPassword());
        self::assertSame('https', $c->Scheme);
        self::assertSame('ca.pem', $c->CAFile);
        self::assertSame('certs/', $c->CAPath);
        self::assertSame('client.crt', $c->CertFile);
        self::assertSame('client.key', $c->KeyFile);
        self::assertSame('consul.test', $c->TLSServerName);
        self::assertTrue($c->InsecureSkipVerify);
    }

    public function testNamespaceAndPartitionFromEnv(): void
    {
        $this->setEnv(name: Consul::HTTPNamespaceEnvName, value: 'env-ns');
        $this->setEnv(name: Consul::HTTPPartitionEnvName, value: 'env-part');
        $c = new Config();
        self::assertSame('env-ns', $c->Namespace);
        self::assertSame('env-part', $c->Partition);
    }

    public function testTokenFileFromEnv(): void
    {
        $this->setEnv(name: Consul::HTTPTokenFileEnvName, value: '/env/token.file');
        $c = new Config();
        self::assertSame('/env/token.file', $c->TokenFile);
    }

    public function testEmptyEnvValuesAreTreatedAsUnset(): void
    {
        $this->setEnv(name: Consul::HTTPAddrEnvName, value: '');
        $this->setEnv(name: Consul::HTTPTokenEnvName, value: '');
        $this->setEnv(name: Consul::HTTPAuthEnvName, value: '');
        $c = new Config();
        self::assertSame(Config::DEFAULT_ADDRESS, $c->Address);
        self::assertSame('', $c->Token);
        self::assertNull($c->HttpAuth);
    }

    /**
     * @return array<string,array{string}>
     */
    public static function goTrueValues(): array
    {
        $out = [];
        foreach (['1', 't', 'T', 'TRUE', 'true', 'True'] as $v) {
            $out[$v] = [$v];
        }
        return $out;
    }

    /**
     * @return array<string,array{string}>
     */
    public static function goFalseValues(): array
    {
        $out = [];
        foreach (['0', 'f', 'F', 'FALSE', 'false', 'False'] as $v) {
            $out[$v] = [$v];
        }
        return $out;
    }

    /**
     * Values Go's strconv.ParseBool rejects.
     *
     * @return array<string,array{string}>
     */
    public static function goInvalidBoolValues(): array
    {
        $out = [];
        foreach (['yes', 'no', 'on', 'off', 'tRuE', ' true', 'garbage', '2'] as $v) {
            $out[$v] = [$v];
        }
        return $out;
    }

    #[DataProvider('goTrueValues')]
    public function testEnvSSLTrueValuesUseHTTPS(string $value): void
    {
        $this->setEnv(name: Consul::HTTPSSLEnvName, value: $value);
        self::assertSame('https', (new Config())->Scheme);
    }

    #[DataProvider('goFalseValues')]
    public function testEnvSSLFalseValuesUseHTTP(string $value): void
    {
        $this->setEnv(name: Consul::HTTPSSLEnvName, value: $value);
        self::assertSame('http', (new Config())->Scheme);
    }

    #[DataProvider('goInvalidBoolValues')]
    public function testEnvSSLInvalidValuesUseHTTP(string $value): void
    {
        $this->setEnv(name: Consul::HTTPSSLEnvName, value: $value);
        self::assertSame('http', (new Config())->Scheme);
    }

    #[DataProvider('goTrueValues')]
    public function testEnvSSLVerifyTrueValuesKeepVerify(string $value): void
    {
        $this->setEnv(name: Consul::HTTPSSLVerifyEnvName, value: $value);
        self::assertFalse((new Config())->InsecureSkipVerify);
    }

    #[DataProvider('goFalseValues')]
    public function testEnvSSLVerifyFalseValuesSkipVerify(string $value): void
    {
        $this->setEnv(name: Consul::HTTPSSLVerifyEnvName, value: $value);
        self::assertTrue((new Config())->InsecureSkipVerify);
    }

    #[DataProvider('goInvalidBoolValues')]
    public function testEnvSSLVerifyInvalidValuesSkipVerify(string $value): void
    {
        $this->setEnv(name: Consul::HTTPSSLVerifyEnvName, value: $value);
        self::assertTrue((new Config())->InsecureSkipVerify);
    }

    public function testEnvSSLVerifyEmptyStringKeepsVerify(): void
    {
        $this->setEnv(name: Consul::HTTPSSLVerifyEnvName, value: '');
        self::assertFalse((new Config())->InsecureSkipVerify);
    }

    public function testExplicitDefaultAddressIsNotOverriddenByEnv(): void
    {
        $this->setEnv(name: Consul::HTTPAddrEnvName, value: '192.168.99.99:8500');
        $c = new Config(Address: Config::DEFAULT_ADDRESS);
        self::assertSame(Config::DEFAULT_ADDRESS, $c->getAddress());
    }

    public function testExplicitDefaultSchemeIsNotOverriddenByEnv(): void
    {
        $this->setEnv(name: Consul::HTTPSSLEnvName, value: '1');
        $c = new Config(Scheme: Config::DEFAULT_SCHEME);
        self::assertSame(Config::DEFAULT_SCHEME, $c->getScheme());
    }

    public function testExplicitEmptyTokenIsNotOverriddenByEnv(): void
    {
        $this->setEnv(name: Consul::HTTPTokenEnvName, value: 'env-token');
        $c = new Config(Token: '');
        self::assertSame('', $c->getToken());
    }

    public function testExplicitFalseSkipVerifyOverridesEnvInConstructor(): void
    {
        $this->setEnv(name: Consul::HTTPSSLVerifyEnvName, value: 'false');
        $c = new Config(InsecureSkipVerify: false);
        self::assertFalse($c->isInsecureSkipVerify());
    }

    public function testExplicitTrueSkipVerifyOverridesEnv(): void
    {
        $this->setEnv(name: Consul::HTTPSSLVerifyEnvName, value: 'true');
        $c = new Config(InsecureSkipVerify: true);
        self::assertTrue($c->isInsecureSkipVerify());
    }

    /**
     * Port of the Go client's TestAPI_GenerateEnv.
     */
    public function testGenerateEnv(): void
    {
        $c = new Config(
            Address: '127.0.0.1:8500',
            Scheme: 'http',
            Token: 'test',
            TokenFile: 'test.file',
            CAFile: '',
            CertFile: '',
            KeyFile: '',
            InsecureSkipVerify: true,
            CAPath: '',
            TLSServerName: '',
        );

        self::assertSame(
            [
                'CONSUL_HTTP_ADDR=127.0.0.1:8500',
                'CONSUL_HTTP_TOKEN=test',
                'CONSUL_HTTP_TOKEN_FILE=test.file',
                'CONSUL_HTTP_SSL=false',
                'CONSUL_CACERT=',
                'CONSUL_CAPATH=',
                'CONSUL_CLIENT_CERT=',
                'CONSUL_CLIENT_KEY=',
                'CONSUL_TLS_SERVER_NAME=',
                'CONSUL_HTTP_SSL_VERIFY=false',
                'CONSUL_HTTP_AUTH=',
            ],
            $c->generateEnv(),
        );
    }

    /**
     * Port of the Go client's TestAPI_GenerateEnvHTTPS.
     */
    public function testGenerateEnvHTTPS(): void
    {
        $c = new Config(
            Address: '127.0.0.1:8500',
            Scheme: 'https',
            HttpAuth: new HttpAuth(username: 'user', password: 'password'),
            Token: 'test',
            TokenFile: 'test.file',
            CAFile: '/var/consul/ca.crt',
            CertFile: '/var/consul/server.crt',
            KeyFile: '/var/consul/ssl/server.key',
            InsecureSkipVerify: false,
            CAPath: '/var/consul/ca.dir',
            TLSServerName: '127.0.0.1:8500',
        );

        self::assertSame(
            [
                'CONSUL_HTTP_ADDR=127.0.0.1:8500',
                'CONSUL_HTTP_TOKEN=test',
                'CONSUL_HTTP_TOKEN_FILE=test.file',
                'CONSUL_HTTP_SSL=true',
                'CONSUL_CACERT=/var/consul/ca.crt',
                'CONSUL_CAPATH=/var/consul/ca.dir',
                'CONSUL_CLIENT_CERT=/var/consul/server.crt',
                'CONSUL_CLIENT_KEY=/var/consul/ssl/server.key',
                'CONSUL_TLS_SERVER_NAME=127.0.0.1:8500',
                'CONSUL_HTTP_SSL_VERIFY=true',
                'CONSUL_HTTP_AUTH=user:password',
            ],
            $c->generateEnv(),
        );
    }
}
