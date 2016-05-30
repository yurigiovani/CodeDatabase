<?php

namespace CodePress\CodeDatabase\Contracts;

interface CriteriaCollectionInterface
{
    public function addCriteria(CriteriaInterface $criteria);

    public function getCriteriaCollection();

    public function getByCriteria(CriteriaInterface $criteria);

    public function applyCriteria();

    public function ignoreCriteria($isIgnore = true);

    public function clearCriteria();
}