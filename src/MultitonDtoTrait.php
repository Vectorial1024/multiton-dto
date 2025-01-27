<?php

/**
 * The trait for Multiton DTOs to ergonomically deduplicate DTO instances.
 * 
 * Note: we expect the DTOs that use this trait to be `readonly`, so that their instances can be deduplicated and shared.
 */
trait MultitonDtoTrait {
    /**
     * The ID of this DTO. This identifies the DTO instance from other DTO instances.
     * @var string
     */
    private string|null $dtoID = null;

    /**
     * Returns the ID of this DTO for internal use.
     * @return string The ID of this DTO.
     */
    private function getDtoID(): string
    {
        return $this->dtoID ??= $this->provideDtoID();
    }

    /**
     * Provides the ID of this DTO for multiton sharing and deduplication.
     * 
     * Note: we expect the ID to be `readonly`.
     * @return string The ID of this DTO.
     */
    abstract protected function provideDtoID(): string;
}
