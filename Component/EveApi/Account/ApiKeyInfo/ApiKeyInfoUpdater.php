<?php
namespace Tarioch\EveapiFetcherBundle\Component\EveApi\Account\APIKeyInfo;

use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\ORM\EntityManager;
use Tarioch\EveapiFetcherBundle\Entity\ApiCall;
use Pheal\Pheal;
use Pheal\Core\Element;
use Tarioch\EveapiFetcherBundle\Component\EveApi\KeyApi;
use Tarioch\EveapiFetcherBundle\Entity\ApiKey;
use Tarioch\EveapiFetcherBundle\Entity\AccountAPIKeyInfo;
use Tarioch\EveapiFetcherBundle\Entity\AccountCharacter;
use Tarioch\EveapiFetcherBundle\Entity\Api;

/**
 * @DI\Service("tarioch.eveapi.account.APIKeyInfo")
 */
class ApiKeyInfoUpdater implements KeyApi
{

    private $entityManager;
    private $currentApiCallFactory;

    /**
     * @DI\InjectParams({
     * "entityManager" = @DI\Inject("doctrine.orm.eveapi_entity_manager"),
     * "currentApiCallFactory" = @DI\Inject("tarioch.eveapi.account.api_key_info.current_api_call_factory")
     * })
     */
    public function __construct(EntityManager $entityManager, CurrentApiCallFactory $currentApiCallFactory)
    {
        $this->entityManager = $entityManager;
        $this->currentApiCallFactory = $currentApiCallFactory;
    }

    /**
     * @inheritdoc
     */
    public function update(ApiCall $call, ApiKey $key, Pheal $pheal)
    {
        $api = $pheal->accountScope->APIKeyInfo();
        $apiKey = $api->key;

        $this->updateApiKeyInfo($key, $apiKey);

        // update associated characters
        $repository = $this->entityManager->getRepository('TariochEveapiFetcherBundle:AccountCharacter');
        $characterEntities = $repository->findByKey($key);
        $charEntityMap = array();
        foreach ($characterEntities as $characterEntity) {
            $charEntityMap[$characterEntity->getCharacterId()] = $characterEntity;
        }

        $chars = array();
        $corps = array();
        foreach ($apiKey->characters as $char) {
            $charId = $char->characterID;
            if (isset($charEntityMap[$charId])) {
                $charEntity = $charEntityMap[$charId];
                unset($charEntityMap[$charId]);
            } else {
                $charEntity = new AccountCharacter($key, $charId);
                $this->entityManager->persist($charEntity);
            }
            $chars[] = $charId;

            $charEntity->setCharacterName($char->characterName);

            $corpId = $char->corporationID;
            $charEntity->setCorporationId($corpId);
            $corps[] = $corpId;
            $charEntity->setCorporationName($char->corporationName);
        }

        // remove old, no longer valid characters
        foreach ($characterEntities as $characterEntity) {
            $this->entityManager->remove($characterEntity);
        }

        $this->updateApiCalls($key, $apiKey->accessMask, $chars, $corps);

        return $api->cached_until;
    }

    private function updateApiCalls(ApiKey $key, $accessMask, array $chars, array $corps)
    {
        $currentApiCallMap = $this->currentApiCallFactory->createCurrentApiCallMap($key);

        $apiRepo = $this->entityManager->getRepository('TariochEveapiFetcherBundle:Api');
        $validApis = $apiRepo->loadValidApis($accessMask);
        foreach ($validApis as $api) {
            $section = $api->getSection();
            $owners;
            if ($section === 'account') {
                $owners = $key->getKeyId();
            } elseif ($section === 'char') {
                $owners = $chars;
            } elseif ($section === 'corp') {
                $owners = $corps;
            }

            $apiId = $api->getApiId();
            if (isset($currentApiCallMap[$apiId])) {
                $currentOwners = array_keys($currentApiCallMap[$apiId]);
                $addedOwners = array_diff($owners, $currentOwners);
                foreach ($addedOwners as $owner) {
                    $this->entityManager->persist(new ApiCall($api, $owner));
                }

                $removedOwners = array_diff($currentOwners, $owners);
                foreach ($removedOwners as $owner) {
                    $this->entityManager->remove($currentApiCallMap[$apiId][$owner]);
                }
                unset($currentApiCallMap[$apiId]);
            } else {
                foreach ($owners as $owner) {
                    $this->entityManager->persist(new ApiCall($api, $owner));
                }
            }
        }

        foreach ($currentApiCallMap as $apis) {
            foreach ($apis as $calls) {
                $this->entityManager->remove($calls);
            }
        }
    }

    private function updateApiKeyInfo(ApiKey $key, Element $apiKey)
    {
        $entity = $this->loadOrCreate($key);
        $entity->setAccessMask($apiKey->accessMask);
        if (! empty($apiKey->expires)) {
            $entity->setExpires(new \DateTime($apiKey->expires));
        }
        $entity->setType($apiKey->type);
    }

    private function loadOrCreate(ApiKey $key)
    {
        $repository = $this->entityManager->getRepository('TariochEveapiFetcherBundle:AccountAPIKeyInfo');
        $entity = $repository->findOneByKey($key);
        if ($entity == null) {
            $entity = new AccountAPIKeyInfo($key);
            $this->entityManager->persist($entity);
        }

        return $entity;
    }
}