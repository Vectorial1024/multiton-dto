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

    abstract function getDtoID();

    /**
     * Provides the ID of this DTO for multiton sharing and deduplication.
     * @return string The ID of this DTO.
     */
    abstract protected function provideDtoID(): string;
}
