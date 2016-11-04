<?php
/**
 *
 */

namespace LiveScore;


class Cricket
{
    /**
     * @var array
     */
    protected $matches = [];

    /**
     * @return array
     */
    public function getTodaysMatches()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'http://www.espncricinfo.com/netstorage/summary.json',
            CURLOPT_USERAGENT => 'Live Cricket Score'
        ));
        $response = json_decode(curl_exec($curl));
        curl_close($curl);

        $modules = $response->modules->aus;
        $allMatches = (array)$response->matches;

        foreach ($modules as $module) {
            if ($module->category == 'intl') {
                $this->matches = array_merge($this->matches, $this->analysisMatch($module->matches, $allMatches, 'international'));
            }
            if ($module->category == 'dom') {
                if ($module->title == 'Domestic') {
                    foreach ($module->submenu as $domestic) {
                        $this->matches = array_merge($this->matches, $this->analysisMatch($domestic->matches, $allMatches, 'domestic', $domestic->title));
                    }
                } else {
                    $this->matches = array_merge($this->matches, $this->analysisMatch($module->matches, $allMatches, 'other'));
                }
            }
        }

        return $this->matches;
    }

    /**
     * @param $moduleMatches
     * @param $allMatches
     * @param $type
     * @param null $title
     * @return array
     */
    protected function analysisMatch($moduleMatches, $allMatches, $type, $title = null)
    {
        $singleMatch = array();
        foreach ($moduleMatches as $matchID) {
            foreach ($allMatches as $key => $value) {
                if ($matchID == $key) {
                    $matchDetails = (array)$value;
                    $url = explode('/match/', $matchDetails['url']);
                    $urlID = (int)str_replace('.html', '', $url[1]);

                    $matchDetails['id'] = (int)$matchID;
                    $matchDetails['urlID'] = $urlID;
                    $matchDetails['type'] = $type;
                    $matchDetails['title'] = $title;
                    $singleMatch[] = $matchDetails;
                }
            }
        }
        return $singleMatch;
    }

    /**
     * @param $matchId
     * @param $matchType
     * @return mixed
     */
    public function getMatchScore($matchId, $matchType)
    {
        if ($matchType == 'international') {
            $url = 'http://www.espncricinfo.com/netstorage/' . $matchId . '.json?xhr=1';
        } else {
            $url = 'http://www.espncricinfo.com/ci/engine/match/' . $matchId . '.json?xhr=1';
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => 'Codular Sample cURL Request'
        ));
        $response = json_decode(curl_exec($curl), true);
        curl_close($curl);
        return $response;
    }
}