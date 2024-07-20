<?php
/*
    This file is part of Symbiose Community Edition <https://github.com/yesbabylon/symbiose>
    Some Rights Reserved, Yesbabylon SRL, 2020-2021
    Licensed under GNU AGPL 3 license <http://www.gnu.org/licenses/>
*/
namespace sale\booking;
use equal\orm\Model;

class Composition extends Model {

    public static function getName() {
        return 'Composition';
    }

    public static function getDescription() {
        return "A Composition is an exhaustive list of persons that participate to a sojourn related to a Booking.";
    }
        
    public static function getColumns() {
        return [
            'name' => [
                'type'              => 'computed',
                'function'          => 'sale\booking\Composition::getDisplayName',
                'result_type'       => 'string',
                'store'             => true,
                'description'       => 'Composition name is based on the related booking (customer and date).'
            ],

            'booking_id' => [
                'type'              => 'many2one',
                'foreign_object'    => 'sale\booking\Booking',
                'description'       => 'The booking the composition relates to.' 
            ],

            'booking_line_group_id' => [
                'type'              => 'many2one',
                'foreign_object'    => 'sale\booking\BookingLineGroup',
                'description'       => 'The group the composition relates to.' 
            ],

            'composition_items_ids' => [
                'type'              => 'one2many',
                'foreign_object'    => 'sale\booking\CompositionItem',
                'foreign_field'     => 'composition_id',
                'description'       => "The items that refer to the composition."
            ]

        ];
    }


    public static function getDisplayName($om, $oids, $lang) {
        $result = [];
        $compositions = $om->read(__CLASS__, $oids, ['booking_id.customer_id.name', 'booking_id.date_from']);
        foreach($compositions as $oid => $odata) {
            $result[$oid] = $odata['booking_id.customer_id.name'].'_'.$odata['booking_id.date_from'];
        }
        return $result;              
    }    
}