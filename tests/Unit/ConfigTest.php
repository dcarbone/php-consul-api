<?php

namespace DCarbone\PHPConsulAPITests\Unit;

use DCarbone\Go\Time;
use DCarbone\PHPConsulAPI\Consul;
use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\HttpAuth;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ConfigTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $c = new Config();
        self::assertSame(Config::DEFAULT_ADDRESS, $c->getAddress());
        self::assertSame(Config::DEFAULT_ADDRESS, $c->Address);
        self::assertSame(Config::DEFAULT_SCHEME, $c->getScheme());
        self::assertSame(Config::DEFAULT_SCHEME, $c->Scheme);
        self::assertSame('', $c->getDatacenter());
        self::assertSame('', $c->Datacenter);
        self::assertSame('', $c->getNamespace());
        self::assertSame('', $c->Namespace);
        self::assertNull($c->HttpAuth);
        self::assertSame(0.0, $c->getWaitTime()->Seconds());
        self::assertSame('', $c->getToken());
        self::assertSame('', $c->Token);
        self::assertSame('', $c->getTokenFile());
        self::assertSame('', $c->TokenFile);
        self::assertSame('', $c->getCAFile());
        self::assertSame('', $c->CAFile);
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
            TokenFile: '',
            CAFile: '',
            CertFile: '',
            KeyFile: '',
            InsecureSkipVerify: true,
            HttpClient: $httpClient,
            JSONEncodeOpts: 0,
            JSONDecodeMaxDepth: 256,
            JSONDecodeOpts: JSON_BIGINT_AS_STRING,
        );
        self::assertSame('10.0.0.1:8500', $c->getAddress());
        self::assertSame('https', $c->getScheme());
        self::assertSame('dc1', $c->getDatacenter());
        self::assertSame('ns1', $c->getNamespace());
        self::assertInstanceOf(HttpAuth::class, $c->HttpAuth);
        self::assertSame('admin', $c->HttpAuth->getUsername());
        self::assertSame('pass', $c->HttpAuth->getPassword());
        self::assertSame(30.0, $c->getWaitTime()->Seconds());
        self::assertSame('my-token', $c->getToken());
        self::assertTrue($c->isInsecureSkipVerify());
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
            ->setDatacenter('dc2')
            ->setNamespace('ns2')
            ->setWaitTime('15s')
            ->setToken('tok')
            ->setTokenFile('')
            ->setCAFile('/ca.pem')
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
        self::assertSame('dc2', $c->getDatacenter());
        self::assertSame('dc2', $c->Datacenter);
        self::assertSame('ns2', $c->getNamespace());
        self::assertSame('ns2', $c->Namespace);
        self::assertSame(15.0, $c->getWaitTime()->Seconds());
        self::assertSame('tok', $c->getToken());
        self::assertSame('tok', $c->Token);
        self::assertSame('/ca.pem', $c->getCAFile());
        self::assertSame('/ca.pem', $c->CAFile);
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

    public function testNewDefaultConfig(): void
    {
        $c = Config::newDefaultConfig();
        self::assertInstanceOf(Config::class, $c);
        self::assertSame(Config::DEFAULT_ADDRESS, $c->getAddress());
        self::assertSame(Config::DEFAULT_SCHEME, $c->getScheme());
    }

    public function testEnvInsecureSkipVerifySupportsNumericTrueString(): void
    {
        self::setEnv(name: Consul::HTTPSSLVerifyEnvName, value: '1');
        try {
            $c = new Config();
            self::assertTrue($c->isInsecureSkipVerify());
        } finally {
            self::clearEnv(Consul::HTTPSSLVerifyEnvName);
        }
    }

    public function testExplicitDefaultAddressIsNotOverriddenByEnv(): void
    {
        self::setEnv(name: Consul::HTTPAddrEnvName, value: '192.168.99.99:8500');
        try {
            $c = new Config(Address: Config::DEFAULT_ADDRESS);
            self::assertSame(Config::DEFAULT_ADDRESS, $c->getAddress());
        } finally {
            self::clearEnv(Consul::HTTPAddrEnvName);
        }
    }

    public function testExplicitFalseSkipVerifyOverridesEnv(): void
    {
        self::setEnv(name: Consul::HTTPSSLVerifyEnvName, value: '1');
        try {
            $c = new Config(InsecureSkipVerify: false);
            self::assertFalse($c->isInsecureSkipVerify());
        } finally {
            self::clearEnv(Consul::HTTPSSLVerifyEnvName);
        }
    }

    private static function setEnv(string $name, string $value): void
    {
        putenv(sprintf('%s=%s', $name, $value));
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
    }

    private static function clearEnv(string $name): void
    {
        putenv($name);
        unset($_ENV[$name], $_SERVER[$name]);
    }
}
