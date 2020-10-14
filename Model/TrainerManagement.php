<?php

namespace Icube\TrainingApi\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Api\SortOrder;
use Icube\TrainingApi\Api\TrainerManagementInterface;
use Icube\TrainingApi\Api\Data\TrainerInterface;
use Icube\TrainingApi\Api\Data\TrainerSearchResultsInterfaceFactory;
use Icube\TrainingApi\Model\TrainerFactory;

class TrainerManagement implements TrainerManagementInterface
{
    /**
     * @var TrainerFactory
     */
	protected $trainerFactory;

    /**
     * @var TrainerSearchResultsInterfaceFactory
     */
	protected $searchResultFactory;

	public function __construct(
		TrainerFactory $trainerFactory,
        TrainerSearchResultsInterfaceFactory $searchResultFactory
    ) {
		$this->trainerFactory = $trainerFactory;
        $this->searchResultFactory = $searchResultFactory;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTrainers(SearchCriteriaInterface $searchCriteria)
	{
        $searchResults = $this->searchResultFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        $collection = $this->trainerFactory->create()->getCollection();

        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ?: 'eq';
                $collection->addFieldToFilter(
                    $filter->getField(),
                    [$condition => $filter->getValue()]
                );
            }
        }

        $searchResults->setTotalCount($collection->getSize());

        $sortOrdersData = $searchCriteria->getSortOrders();
        if ($sortOrdersData) {
            foreach ($sortOrdersData as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }

        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());

        $searchResults->setItems($collection->getData());
        
        return $searchResults;
	}

    /**
	 * {@inheritdoc}
	 */
	public function getTrainerById($id)
	{
        $trainer = $this->trainerFactory->create();
        $trainer->load($id);
		return $trainer;
	}


    /**
	 * {@inheritdoc}
	 */
	public function deleteTrainerById($id)
	{
        $trainer = $this->trainerFactory->create();
        $trainer->load($id);
        $trainer->delete();
		return $trainer;
    }
    
       /**
	 * {@inheritdoc}
	 */
	public function deleteTrainerByName($name)
	{
        $collection = $this->trainerFactory->create()->getCollection();
        $collection->addFieldToFilter('name', $name);
        $countDelete = 0;
        foreach($collection as $trainer) {
            $delete = $trainer->delete();
            if ($delete) $countDelete += 1;
        }

        return 'Success delete trainer : '.$countDelete;
    }
    
   
    /**
	 * {@inheritdoc}
	 */
	public function postTrainer(TrainerInterface $trainer)
    {
        $this->trainerValidate($trainer);
        $trainer->save();
        return $trainer;
    }
    

     /**
	 * {@inheritdoc}
	 */
	public function updateTrainerById($id, TrainerInterface $trainer)
	{
        $validate = $this->trainerUpdateValidate($id, $trainer);
        if (array_values($validate)[0]) {
            return array_values($validate)[1];
        } else {
            $collection = $this->trainerFactory->create();
            $data = $collection->load($id);
            if ($data->getId()) {
                $data->setName($trainer->getName());
                $data->setDivisi($trainer->getDivisi());
                $data->setHobby($trainer->getHobby());
                $data->save();
            } else {
                throw new LocalizedException(
                    __("Data not found.")
                );
            }

            return $data;
        }
	}


    /**
	 * Trainer validate
     *
	 * @param Icube\TrainingApi\Api\Data\TrainerInterface $trainer
	 * @return Icube\TrainingApi\Api\Data\TrainerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
	 */
    protected function trainerValidate(TrainerInterface $trainer)
    {
        if (empty($trainer->getName())) {
            throw new LocalizedException(
                __("The name Can't be empty.")
            );
        }

        if (empty($trainer->getDivisi())) {
            throw new LocalizedException(
                __("The civisi Can't be empty.")
            );
        }

        if (empty($trainer->getHobby())) {
            throw new LocalizedException(
                __("The hobby Can't be empty.")
            );
        }

        return $trainer;
    }

    /**
	 * Trainer update validate
     *
     * @param int $id
	 * @param Icube\TrainingApi\Api\Data\TrainerInterface $trainer
	 * @return Icube\TrainingApi\Api\Data\TrainerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
	 */

    protected function trainerUpdateValidate($id, TrainerInterface $trainer)
    {
        $error = false;
        $errorMessage = '';
        if (empty($id)) {
            $error = true;
            $errorMessage = 'Trainer id is required';
        }

        return [
            "error" => $error,
            "message" => $errorMessage,
        ];
    }
}
