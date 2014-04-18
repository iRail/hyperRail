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
     * For this first version, we return an array with all q values as key
     * which contain the list of mimes requested. Example array that is returned:
     *
     Array (
     [0.9] => Array (
            [0] => text/html
            [1] => application/xhtml+xml
            [2] => application/xml
     )
     [0.8] => Array (
           [0] => image/webp )
     )
     *
     * @param AcceptHeaderParsedArray $acceptHeader
     * @return array
     */
    static function evaluate(AcceptHeaderParsedArray $acceptHeader){
        // First, loop through items and apply quality parameters if they are not set.
        // (AcceptHeader class leaves empty params if q is not specifically set)
        $parsedHeaders = self::fillEmptyQParams($acceptHeader);
        // Now, group requested formats per quality request
        usort($parsedHeaders, "self::uSortByQ");
        // Next, filter mime-types per q requested
        $mimes = array();       // Array of all mime-types per q (as key)
        $mimesAlike = array();  // Array of mime-types that have same q values
                                // Will be pushed onto $mimes, when q is different
        $prevQ = null;          // Previously measured q
        foreach ($parsedHeaders as $header){
            if (array_key_exists("q", $header['params'])){
                // Get current q and mime-type
                $currentQ = $header['params']['q'];
                $mimeType = ($header['type'] . "/" . $header['subtype']);
                // Check if there is a new Q to be saved
                if ($currentQ !== $prevQ){
                    // If a new q is found, create new $mimesAlike array
                    // in which to store similar values
                    $mimesAlike = array();
                }
                // Push mime-type to array wit
                array_push($mimesAlike, $mimeType);
                // Set key value pair (will override each time q is unchanged)
                $mimes[$currentQ] = $mimesAlike;
                // Set previous q encountered to this q
                // and restart
                $prevQ = $currentQ;
            }
        }
        // TODO: calculate server-side q values depending on format?
        // Sort mimes by number: higher is better
        krsort($mimes);
        // Finally, return mimetypes as array that can be used
        // to determine which format needs to be sent to person who
        // requested the resource
        return $mimes;
    }

    static function uSortByQ($a, $b) {
        return $a["params"]["q"] - $b["params"]["q"];
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
            // Check for q params
            if (array_key_exists("q", $item['params'])){
                $prev_params = $item['params'];
            }
            else{
                // Parameter doesn't exist for current item, apply recursively
                if ($prev_params !== null){
                    // Assuming $params is set at all (if no q setting is found)
                    $item['params'] = $prev_params;
                }
                // In case no q params are sent at all, default to q=1
                if ($prev_params == null && $item['params'] == null){
                    $item['params'] = ["q" => 1];
                }
            }
            array_push($parsedHeaders, $item);
        }
        return $parsedHeaders;
    }

} 