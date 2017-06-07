<?php


namespace App\Models\Marketing;


use App\Models\Marketing\Filters\CampaignActionFilter;
use Illuminate\Http\Request;

class CampaignActionCollection extends CampaignActionEntity{

    public function getCollection( Request $r )
    {
        $this->setLpo( $r );
        $this->fields = [ 'a.*' ];

        $this->query = static::from( $this->table.' as a' );

        // campaignid is required
        // can be overridden by no campaign id
        if( ! $r->campaignid && ! $r->get_all ){
            return [];
        }

        $this->query = ( new CampaignActionFilter( $this->query ) )->applyFilter( $r->all() );
        $this->total = $this->query->count();

        $this->assignLpo();
        return $this->query->get( $this->fields );
    }


}