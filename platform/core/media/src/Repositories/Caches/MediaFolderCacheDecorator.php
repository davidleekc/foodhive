<?php

namespace Botble\Media\Repositories\Caches;

use Botble\Media\Repositories\Interfaces\MediaFolderInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class MediaFolderCacheDecorator extends CacheAbstractDecorator implements MediaFolderInterface
{
    public function getFolderByParentId($folderId, array $params = [], $withTrash = false)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function createSlug($name, $parentId)
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    public function createName($name, $parentId)
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    public function getBreadcrumbs($parentId, $breadcrumbs = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getTrashed($parentId, array $params = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function deleteFolder($folderId, $force = false)
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    public function getAllChildFolders($parentId, $child = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getFullPath($folderId, $path = '')
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function restoreFolder($folderId)
    {
        $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    public function emptyTrash()
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }
}
