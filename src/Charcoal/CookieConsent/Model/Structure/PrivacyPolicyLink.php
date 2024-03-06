<?php

namespace Charcoal\CookieConsent\Model\Structure;

/**
 * Structure Model: Privacy Policy Link
 */
class PrivacyPolicyLink extends Link
{
    public const DATETIME_FORMAT = 'YmdHis';

    public function findTime(): ?string
    {
        switch ($this->getType()) {
            case self::TYPE_FILE:
                return $this->findTimeForFile();

            case self::TYPE_MODEL:
                return $this->findTimeForModel();

            case self::TYPE_URL:
                return $this->findTimeForUrl();
        }

        return null;
    }

    protected function findTimeForFile(): ?string
    {
        $file = $this->getFilePath();
        if (!$file) {
            return null;
        }

        /** @var \Charcoal\Property\FileProperty */
        $property = $this->property('filePath');

        $file = $property['basePath'] . '/' . $file;
        if (!\file_exists($file)) {
            return '';
        }

        $time = \filemtime($file);
        if ($time === false) {
            return '';
        }

        return \date(self::DATETIME_FORMAT, $time);
    }

    protected function findTimeForModel(): ?string
    {
        $time = $this->getModel()['lastModified'];
        if (!$time) {
            return null;
        }

        return $time->format(self::DATETIME_FORMAT);
    }

    protected function findTimeForUrl(): ?string
    {
        return null;
    }
}
