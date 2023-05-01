<?php

namespace Dev\Larabit;

use Bitrix\Main\Data\Cache;
use Bitrix\Main\Localization\Loc;
use Exception;
use ReflectionException;
\Bitrix\Main\Localization\Loc::loadMessages(__FILE__);

class Dumper
{
    const TYPE_CONFIG = 'config';
    const TYPE_DUMPED = 'dumped';
    private const AllowedTypes = [
        self::TYPE_CONFIG,
        self::TYPE_DUMPED
    ];
    private const ext = 'dump';
    private const ttl = 60 * 60 * 24 * 3;
    private const lifeTime = 60 * 60 * 24 * 5;

    private string $subPath = '';
    private string $type = self::TYPE_DUMPED;

    public function __construct(string $subPath = '')
    {
        $this->subPath = $subPath;
    }

    /**
     * @throws Exception
     */
    public function setType(string $type = self::TYPE_DUMPED): Dumper
    {
        if ( !in_array($type, self::AllowedTypes) )
        {
            throw new Exception(Loc::getMessage('DEV_LARABIT_DUMPER_TYPE_FAULT') . implode(', ', self::AllowedTypes));
        }
        $this->type = $type;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $name
     * @param $object
     * @return string|null
     * @throws ReflectionException
     * @throws Exception
     */
    public static function make(string $name, $object): ?string
    {
        $object = base64_encode(serialize($object));
        $cacheId = md5($object);

        $cache = new self($name);
        $cache->set($cacheId, $object);
        usleep(100);
        $cacheResult = $cache->get($cacheId) == $object;

        if (!$cacheResult) {
            $cache = Cache::createInstance();
            $cache->forceRewriting(true);
            $cache->startDataCache(self::ttl, $cacheId, $name);
            $cache->endDataCache($object);
            $r = new Reflect($cache);
            if ($r->getProtectedProperty('filename')) {
                $path = __DIR__ . './../../../..'
                    . $r->getProtectedProperty('baseDir')
                    . $r->getProtectedProperty('initDir')
                    . $r->getProtectedProperty('filename');
            }
            $cacheResult = file_exists($path);
        }

        return $cacheId || $cacheResult ? $cacheId : false;
    }

    public static function take(string $name, string $md5)
    {
        $cache = new self($name);
        $string = $cache->get($md5);

        // emergency caching
        if (!$string) {
            $cache = Cache::createInstance();
            $cache->initCache(static::ttl, $md5, $name);
            $string = $cache->getVars();
        }

        if (!$string) {
            $cache->clean($md5, $name);
            return null;
        }

        $object = unserialize(base64_decode($string));
        $cache->clean($md5, $name);

        return $object;
    }

    private function getDir(): string
    {
        return __DIR__ . '/./../' . $this->getType() . DIRECTORY_SEPARATOR . $this->getSubPath();
    }

    private function getExt(): string
    {
        return '.' . self::ext;
    }

    private function setSubPath($path): void
    {
        $this->subPath = $path;
    }

    private function getSubPath(): string
    {
        return $this->subPath ? $this->subPath . DIRECTORY_SEPARATOR : '';
    }

    /**
     * @throws Exception
     */
    public function set(string $key, $data): bool
    {
        if (is_array($data) || is_object($data)) {
            $data = serialize($data);
        }
        if (!$this->checkDir()) {
            throw new Exception('Failed to access directory ' . $this->getDir());
        }
        return file_put_contents($this->getDir() . $key . $this->getExt(), $data);
    }

    public function get(string $key): string
    {
        return file_get_contents($this->getDir() . $key . $this->getExt());
    }

    public function clean(string $key, string $subPath = ''): bool
    {
        if ($subPath) {
            $this->setSubPath($subPath);
        }
        unlink($this->getDir() . $key . $this->getExt());
        if (self::getDirContents($this->getDir())) {
            rmdir($this->getDir());
        }
        $this->scrap_remove();
        return true;
    }

    private function checkDir(): bool
    {
        // checking parent dir
        $parentDir = str_ireplace($this->getSubPath(), '', $this->getDir());
        if (!is_dir(substr($parentDir, 0, -1))) {
            mkdir(substr($parentDir, 0, -1));
        }
        // checking full path
        if ($this->getSubPath() && !is_dir(substr($this->getDir(), 0, -1))) {
            mkdir(substr($this->getDir(), 0, -1));
        }
        return true;
    }

    private static function getDirContents($dir, array &$results = []): array
    {
        $files = scandir($dir);
        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if (!is_dir($path)) {
                $results[] = $path;
            } else if ($value != "." && $value != "..") {
                static::getDirContents($path, $results);
                $results[] = $path;
            }
        }
        return $results;
    }

    private function scrap_remove(): void
    {
        if ( $this->getType() === self::TYPE_CONFIG ) return;
        $files = glob($this->getDir() . '*');
        foreach ($files as $file) {
            if (is_file($file) && time() - filemtime($file) >= self::lifeTime) {
                unlink($file);
            }
        }
    }
}