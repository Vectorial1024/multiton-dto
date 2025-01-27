<?php

/**
 * The trait for Multiton DTOs to ergonomically deduplicate DTO instances.
 * 
 * Note: we expect the DTOs that use this trait to be `readonly`, so that their instances can be deduplicated and shared.
 */
trait MultitonDtoTrait {
    /**
     * The primary value of this DTO. This identifies the DTO instance from other DTO instances.
     * 
     * Mental note: primary key <-> primary value
     * @var string
     */
    private string|null $primaryValue = null;

    abstract function getPrimaryValue();

    /**
     * Provides the value of this DTO for multiton sharing and deduplication.
     * 
     * Note: we expect this DTO to be `readonly`.
     * @return string The value of this DTO.
     */
    abstract protected function providePrimaryValue(): string;
}
