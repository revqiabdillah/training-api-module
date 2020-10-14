<?php

namespace Icube\TrainingApi\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Icube\TrainingApi\Api\Data\TrainerInterface;

interface TrainerManagementInterface
{
    /**
	 * GET trainers
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
	 * @return Icube\TrainingApi\Api\Data\TrainerSearchResultsInterface
	 */
	public function getTrainers(SearchCriteriaInterface $searchCriteria);

    /**
	 * GET trainer by id
     *
	 * @param string $id
	 * @return Icube\TrainingApi\Api\Data\TrainerInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException If the trainer id is invalid
	 */
	public function getTrainerById($id);

    /**
	 * Post trainer
     *
	 * @param Icube\TrainingApi\Api\Data\TrainerInterface $trainer
	 * @return Icube\TrainingApi\Api\Data\TrainerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
	 */
	public function postTrainer(TrainerInterface $trainer);

	/**
	 * Delete trainer by id
     *
	 * @param int $id
	 * @return Icube\TrainingApi\Api\Data\TrainerInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException If the trainer id is invalid
	 */
	public function deleteTrainerById($id);

	/**
	 * Delete trainer by name
     *
	 * @param string $name
	 * @return Icube\TrainingApi\Api\Data\TrainerInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException If the trainer name is invalid
	 */
	public function deleteTrainerByName($name);

	 /**
	 * Put trainer by id
     *
	 * @param int $id
	 * @param Icube\TrainingApi\Api\Data\TrainerInterface $trainer
	 * @return Icube\TrainingApi\Api\Data\TrainerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
	 */

	public function updateTrainerById($id, TrainerInterface $trainer);
}
