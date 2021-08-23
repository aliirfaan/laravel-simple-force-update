<?php

namespace aliirfaan\LaravelSimpleForceUpdate\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Version extends Model
{    
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
    public function getVersions($appName = 'default', $platform = null)
    {
        $query = DB::table('lsfu_versions')
                    ->select(['platform', 'min_ver', 'max_ver', 'update_url', 'update_available_msg', 'update_required_msg'])
                    ->whereRaw('LOWER(app_name) = ?', strtolower($appName));

        $result = [];
        if(!is_null($platform)) {
            $query->whereRaw('LOWER(platform) = ?', strtolower($platform));
        } else {
            $query->orderBy('platform', 'asc');
        }

        $result = $query->get();
        
        return $result;
    }
}