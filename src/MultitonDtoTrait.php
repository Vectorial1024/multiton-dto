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

    /**
     * The multiton table for storing the DTO instances.
     * @var array<string, WeakReference<self>>
     */
    private static $multitonTable = [];

    /**
     * Returns the shared multiton DTO instance with the same ID of `$this`. This therefore deduplicates `$this`.
     * 
     * If no such DTO instance exists, then `$this` is remembered for future sharing.
     * @return self The "flattened" multiton DTO instance.
     */
    final public function toMultiton(): self
    {
        $dtoID = $this->getDtoID();
        // checks whether the existing WeakReference is valid
        $existingRef = static::$multitonTable[$dtoID] ?? null;
        if ($existingRef == null) {
            // first time appearing; remember it and return
            static::$multitonTable[$dtoID] = WeakReference::create($this);
            return $this;
        }
        // we have a previous reference; see if it is still valid
        $theInstance = $existingRef->get();
        if ($theInstance === null) {
            // previous instance expired; remember it and return
            static::$multitonTable[$dtoID] = WeakReference::create($this);
            return $this;
        }
        // previous instance still valid; return that
        return $theInstance;
    }

    /**
     * Performs a garbage collection on the multiton instance table to free up memory of GC-ed multiton instances.
     * @return void
     */
    final public static function cleanMultitons(): void
    {
        // the way PHP arrays work, they will never shrink their sizes even if some of their keys are already unset
        // this means we need a new PHP array to store the un-expired items
        $newTable = [];
        foreach (static::$multitonTable as $dtoValue => $existingRef) {
            if ($existingRef->get() === null) {
                // expired; do not include
                continue;
            }
            // not expired; include in results
            $newTable[$dtoValue] = $existingRef;
        }
        // all items checked; replace table
        static::$multitonTable = $newTable;
    }

    /**
     * Resets the multiton memory entirely. New DTO instances will no longer be deduplicated into the existing DTO instances.
     * @return void
     */
    final public static function resetMultitons(): void
    {
        static::$multitonTable = [];
    }
}
