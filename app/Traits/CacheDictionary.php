<?php

namespace App\Traits;

trait CacheDictionary
{
    private function getDict(): array
    {
        return cache()->get('dict_cache', []);
    }

    private function setDict(array $dict): void
    {
        cache()->set('dict_cache', $dict);
    }

    private function dictAdd(string $key): void
    {
        $dict = $this->getDict();

        if(!in_array($key, $dict)) {
            $dict[] = $key;
            $this->setDict($dict);
        }
    }

    private function dictRemove(string $key): void
    {
        $dict = $this->getDict();
        $dict = array_filter($dict, fn($cache) => $cache !== $key);
        $this->setDict($dict);
    }

    private function dictClear(string $key = ''): void
    {
        $dict = $this->getDict();

        if ($key) {
            // Remove a chave específica
            if (in_array($key, $dict)) {
                cache()->forget($key);
                $this->dictRemove($key); // Remove a chave do cache geral
            }
        } else {
            // Remove todas as chaves
            foreach ($dict as $cacheKey) {
                cache()->forget($cacheKey);
            }
            // Remove o cache geral
            $this->setDict([]);
        }
    }
}