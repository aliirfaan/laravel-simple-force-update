<?php

namespace aliirfaan\LaravelSimpleForceUpdate\Contracts;

use aliirfaan\LaravelSimpleForceUpdate\Models\Version;
use aliirfaan\LaravelSimpleForceUpdate\Services\SemVerService;
use aliirfaan\LaravelSimpleForceUpdate\Exceptions\ComparisonException;

/**
 * AbstractSimpleForceUpdate
 */
abstract class AbstractSimpleForceUpdate
{    
    /**
     * versionModel
     *
     * @var Version
     */
    protected $versionModel;
    
    /**
     * SemVerService
     *
     * @var Service class
     */
    protected $SemVerService;

    public function __construct()
    {
        $this->versionModel = new Version();
        $this->SemVerService = new SemVerService();
    }
        
    /**
     * getVersions
     * 
     * Get versions by application name
     * Get version by application name and platform
     * 
     * @param  string $appName name of the application
     * @param  string $platform name of the platform. Example: android, ios
     * @return array
     */
    public function getVersions($appName = 'default', $platform = 'android')
    {
        $data = [
            'success' => false,
            'result' => null,
            'message' => null
        ];

        $result = $this->versionModel->getVersions($appName, $platform);
        if (!$result->isEmpty()) {
           $formattedResult = [];
           foreach($result->all() as $aResult) {
            $formattedResult[$aResult->platform] = $aResult;
           }
           $data['result'] = $formattedResult;
           $data['success'] = true;
        } else {
           $data['message'] = 'No records found.';
        }

        return $data;
    }
    
    /**
     * getApplicationCompatibility
     * 
     * Compares candidate version with release version and suggest update action
     * 
     * candidate version == max version || candidate version > max version : No action
     * candidate version < max version && candidate version > min version : Action: update available
     * candidate version < min version : Action: update required / force update
     * candidate version == min version : Action: update available
     * 
     * @param  string $candidateVersion candidate version in semanting versioning format
     * @param  string $appName name of the application
     * @param  string $platform name of the platform. Example: android, ios
     * @return void
     */
    public function getApplicationCompatibility($candidateVersion, $appName = 'default', $platform = 'android')
    {
        $data = [
            'success' => false,
            'result' => null,
            'message' => null
        ];

        try {
            $releaseVersionResult = $this->getVersions($appName, $platform);
            if ($releaseVersionResult['success'] == true) {
                $releaseVersion = $releaseVersionResult['result'][ $platform];
                $releaseMinVersion = $releaseVersionResult['result'][ $platform]->min_ver;
                $releaseMaxVersion = $releaseVersionResult['result'][ $platform]->max_ver;

                $updateFeedback['update_url'] = $releaseVersionResult['result'][ $platform]->update_url;
                if ($this->SemVerService->equals($candidateVersion, $releaseMaxVersion) || $this->SemVerService->greaterThan($candidateVersion, $releaseMaxVersion)) {
                    $updateFeedback['action'] = 'no_action';
                    $updateFeedback['title'] = 'no_action';
                    $updateFeedback['message'] = 'No update available, no action required.';
                } elseif ($this->SemVerService->smallerThan($candidateVersion, $releaseMaxVersion) && $this->SemVerService->greaterThan($candidateVersion, $releaseMinVersion)) {
                    $updateFeedback['action'] = 'update_available';
                    $updateFeedback['title'] = $releaseVersionResult['result'][ $platform]->update_available_title;
                    $updateFeedback['message'] = $releaseVersionResult['result'][ $platform]->update_available_msg;
                } elseif ($this->SemVerService->equals($candidateVersion, $releaseMinVersion)) {
                    $updateFeedback['action'] = 'update_available';
                    $updateFeedback['title'] = $releaseVersionResult['result'][ $platform]->update_available_title;
                    $updateFeedback['message'] = $releaseVersionResult['result'][ $platform]->update_available_msg;
                } elseif ($this->SemVerService->smallerThan($candidateVersion, $releaseMinVersion)) {
                    $updateFeedback['action'] = 'update_required';
                    $updateFeedback['title'] = $releaseVersionResult['result'][ $platform]->update_required_title;
                    $updateFeedback['message'] = $releaseVersionResult['result'][ $platform]->update_required_msg;
                }

                $data['success'] = true;
                $data['result'] = $releaseVersionResult['result'];
                $data['result']['compatibility'] = (object) $updateFeedback;
                $data['message'] = 'Record found.';
            } else {
                $data['message'] = $releaseVersionResult['message'];
            }
        } catch (ComparisonException $e) {
            $data['message'] = 'Could not parse semantic version. ' . $e->getMessage();
        }
        
        return $data;
    }
}