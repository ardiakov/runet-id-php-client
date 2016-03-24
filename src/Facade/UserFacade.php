<?php

namespace RunetId\ApiClient\Facade;

use RunetId\ApiClient\ApiClient;
use RunetId\ApiClient\Model\ProfInterest;
use RunetId\ApiClient\Model\User;
use Ruvents\HttpClient\Request\File;

/**
 * Class UserFacade
 * @package RunetId\ApiClient\Facade
 */
class UserFacade extends BaseFacade
{
    /**
     * @var int
     */
    private $runetId;

    /**
     * @param ApiClient $apiClient
     * @param int|null  $runetId
     */
    public function __construct(ApiClient $apiClient, $runetId = null)
    {
        parent::__construct($apiClient);
        $this->runetId = (int)$runetId;
    }

    /**
     * @return User
     */
    public function get()
    {
        $response = $this->apiClient->get('user/get', ['RunetId' => $this->runetId]);

        return $this->processResponse($response, 'user');
    }

    /**
     * @param string|File|resource $file
     * @return User
     */
    public function setPhoto($file)
    {
        return $this->apiClient->post('user/setphoto',
            ['RunetId' => $this->runetId], null, [], ['Image' => $file]
        );
    }

    /**
     * @param int|ProfInterest $profInterestOrId
     */
    public function addProfInterest($profInterestOrId)
    {
        if ($profInterestOrId instanceof ProfInterest) {
            $profInterestOrId = $profInterestOrId->Id;
        }

        $profInterestOrId = (int)$profInterestOrId;

        $this->apiClient->get('professionalinterest/add', [
            'RunetId' => $this->runetId,
            'ProfessionalInterestId' => $profInterestOrId,
        ]);
    }

    /**
     * @param int|ProfInterest $profInterestOrId
     */
    public function removeProfInterest($profInterestOrId)
    {
        if ($profInterestOrId instanceof ProfInterest) {
            $profInterestOrId = $profInterestOrId->Id;
        }

        $profInterestOrId = (int)$profInterestOrId;

        $this->apiClient->get('professionalinterest/delete', [
            'RunetId' => $this->runetId,
            'ProfessionalInterestId' => $profInterestOrId,
        ]);
    }
}