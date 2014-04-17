<?php

/**
 * Created by nicoverbruggen on 17/04/14 15:55
 */

namespace hyperRail\Utils\ContentNegotiation;

use ArrayIterator;

class AcceptHeaderQEvaluator {

    /**
     * Per W3C spec (http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html)
     * we want to evaluate the quality and level parameters
     * set in the accept headers per MIME-type. We already have a great
     * class for this, AcceptHeader, but it still leaves fields open.
     * This way, there is no inheritance! For example:
     *
     * text/html,application/xhtml+xml,application/xml;q=0.9,application/json;q=0.8
     *
     * would still leave "text/html" with no q parameter. However, it should
     * inherit this quality parameter from application/xml, which is 0.9.
     * The evaluate function fixes this, so we can do some comparisons.
     *
     * For this first version, we return an array with all requested file formats
     * and their quality setting.
     *
     * TODO: evaluate the different quality params and group them so that the
     * server can evaluate which kind of MIME-type should be served to the
     * person/client who made the request.
     *
     * @param AcceptHeaderParsedArray $acceptHeader
     * @return array
     */
    static function evaluate(AcceptHeaderParsedArray $acceptHeader){
        // First, loop through items and apply quality parameters if they are not set.
        // (AcceptHeader class leaves empty params if q is not specifically set)
        $parsedHeaders = self::fillEmptyQParams($acceptHeader);
        // Now, group requested formats per quality request
        // For now, let's return the headers in JSON with all their Q's filled
        return $parsedHeaders;
    }

    /**
     * If you provide an accept header array, this static function will be able
     * to fill in the empty quality parameters with the last quality parameter value
     * found. This function will also reverse the array, so keep that in mind!
     *
     * Right now, any other parameters other than the quality parameter will be overwritten
     * if there is no quality parameter set. This is unintentional behaviour and should be changed
     * in future versions.
     *
     * TODO: check carefully if q is the only param; only inherit q param, not all params
     *
     * @param AcceptHeaderParsedArray $acceptHeader
     * @return array
     */
    private static function fillEmptyQParams(AcceptHeaderParsedArray $acceptHeader){
        $reverted = new ArrayIterator(array_reverse((array)$acceptHeader));
        $parsedHeaders = array();
        $prev_params = null;
        foreach ($reverted as $item){
            // Level param checks take priority
            if (array_key_exists("level", $item['params'])){
                $prev_params = $item['params'];
            }
            // Then check for q params
            elseif (array_key_exists("q", $item['params'])){
                $prev_params = $item['params'];
            }
            else{
                // Parameter doesn't exist for current item, apply recursively
                if ($prev_params !== null){
                    // Assuming $params is set at all (if no q setting is found)
                    $item['params'] = $prev_params;
                }
            }
            array_push($parsedHeaders, $item);
        }
        return $parsedHeaders;
    }

} 