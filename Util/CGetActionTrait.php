<?php

namespace Lasso\VmailBundle\Util;

use Doctrine\ORM\EntityRepository;
use JMS\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait CGetActionTrait {

    /**
     * @return EntityRepository
     */
    abstract protected function getRepository();

    /**
     * Shortcut to return the request service.
     *
     * @return Request
     */
    abstract public function getRequest();

    /**
     * Gets a service by id.
     *
     * @param string $id The service id
     *
     * @return object The service
     */
    abstract public function get($id);

    /**
     * @return Response
     */
    public function cgetAction(){
        $request = $this->getRequest();
        $offset  = $request->query->get('start');
        $limit   = $request->query->get('limit');
        $search  = $request->query->get('query');
        $sort    = $request->query->get('sort');
        $sort    = !empty($sort) ? json_decode($sort)[0] : false;

        $repo   = $this->getRepository();
        $count  = $repo->getCount($search);
        $list   = $repo->getList($search, $limit, $offset, $sort);

        /** @var $serializer Serializer */
        $serializer = $this->get('serializer');
        $data = $serializer->serialize($list, 'json');

        return new Response("{\"count\": {$count}, \"data\": {$data}}", 200, ['Content-Type', 'application/json']);
    }
}
