<?php

return [
    /*
    | Slug (en) of property category => fields config
    | Each field: key (name) => [ 'label_key' => __ key, 'type' => select|number|checkbox|range|text, 'options' => [] for select ]
    */
    'apartment' => [
        'building_age' => [
            'label_key' => 'Building Age',
            'type' => 'select',
            'options' => ['new' => 'Building Age: New', '5-10' => '5-10', '10-11' => '10-11', '11-20' => '11-20', '20+' => '20+'],
        ],
        'bedrooms' => ['label_key' => 'Bedrooms', 'type' => 'select', 'options' => ['studio' => 'Studio', '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5+' => '5+']],
        'bathrooms' => ['label_key' => 'Bathrooms', 'type' => 'select', 'options' => ['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5+' => '5+']],
        'size' => ['label_key' => 'Apartment Area (m²)', 'type' => 'range'], // size + size_max
        'floor' => [
            'label_key' => 'Floor Number',
            'type' => 'select',
            'options' => [
                'ground' => 'Ground', 'first' => 'First', 'second' => 'Second', 'third' => 'Third',
                'fourth' => 'Fourth', 'roof' => 'Roof', 'mezzanine_1' => 'Mezzanine 1', 'mezzanine_2' => 'Mezzanine 2',
            ],
        ],
        'furnished' => ['label_key' => 'Furnished', 'type' => 'checkbox', 'options' => ['0' => 'Unfurnished']],
        'amenities' => [
            'label_key' => 'Extra Features',
            'type' => 'checkboxes',
            'options' => [
                'pool' => 'Pool', 'sewage_connected' => 'Sewage Connected', 'water_well' => 'Water Well',
                'balcony' => 'Balcony', 'maid_room' => 'Maid Room', 'storage_room' => 'Storage Room',
                'laundry_room' => 'Laundry Room', 'central_ac' => 'Central AC', 'car_parking' => 'Car Parking',
            ],
        ],
    ],
    'villa' => [
        'building_age' => [
            'label_key' => 'Building Age',
            'type' => 'select',
            'options' => ['new' => 'Building Age: New', '5-10' => '5-10', '10-11' => '10-11', '11-20' => '11-20', '20+' => '20+'],
        ],
        'bedrooms' => ['label_key' => 'Bedrooms', 'type' => 'select', 'options' => ['studio' => 'Studio', '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5+' => '5+']],
        'bathrooms' => ['label_key' => 'Bathrooms', 'type' => 'select', 'options' => ['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5+' => '5+']],
        'land_area' => ['label_key' => 'Land Area (m²)', 'type' => 'range'],
        'size' => ['label_key' => 'Villa/House Area (m²)', 'type' => 'range'],
        'furnished' => ['label_key' => 'Furnished', 'type' => 'checkbox', 'options' => ['0' => 'Unfurnished']],
        'amenities' => [
            'label_key' => 'Extra Features',
            'type' => 'checkboxes',
            'options' => ['pool' => 'Pool', 'sewage_connected' => 'Sewage Connected', 'water_well' => 'Water Well'],
        ],
    ],
    'office' => [
        'building_age' => [
            'label_key' => 'Building Age',
            'type' => 'select',
            'options' => ['new' => 'Building Age: New', '5-10' => '5-10', '10-11' => '10-11', '11-20' => '11-20', '20+' => '20+'],
        ],
        'rooms' => ['label_key' => 'Number of Rooms', 'type' => 'select', 'options' => ['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5+' => '5+']],
        'bathrooms' => ['label_key' => 'Bathrooms', 'type' => 'select', 'options' => ['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5+' => '5+']],
        'size' => ['label_key' => 'Office Area (m²)', 'type' => 'range'],
        'floor' => ['label_key' => 'Floor', 'type' => 'text'],
    ],
    'commercial' => [
        'building_age' => [
            'label_key' => 'Building Age',
            'type' => 'select',
            'options' => ['new' => 'Building Age: New', '5-10' => '5-10', '10-11' => '10-11', '11-20' => '11-20', '20+' => '20+'],
        ],
        'bathrooms' => ['label_key' => 'Bathrooms', 'type' => 'select', 'options' => ['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5+' => '5+']],
        'size' => ['label_key' => 'Storage Area (m²)', 'type' => 'range'],
        'floor' => ['label_key' => 'Floor', 'type' => 'text'],
    ],
    'farm' => [
        'building_age' => ['label_key' => 'Building Age', 'type' => 'text'],
        'bedrooms' => ['label_key' => 'Bedrooms', 'type' => 'number'],
        'size' => ['label_key' => 'Farm Area (m²)', 'type' => 'number'],
        'bathrooms' => ['label_key' => 'Bathrooms', 'type' => 'number'],
        'amenities' => [
            'label_key' => 'Extra Features',
            'type' => 'checkboxes',
            'options' => ['pool' => 'Pool', 'sewage_connected' => 'Sewage Connected', 'water_well' => 'Water Well'],
        ],
    ],
    'land' => [
        'zoning' => [
            'label_key' => 'Zoning',
            'type' => 'select',
            'options' => [
                'residential_a' => 'Residential A', 'residential_b' => 'Residential B', 'residential_c' => 'Residential C',
                'residential_d' => 'Residential D', 'offices' => 'Offices', 'commercial' => 'Commercial',
                'light_industry' => 'Light Industry', 'industrial' => 'Industrial', 'agricultural' => 'Agricultural',
                'outside_planning' => 'Outside Planning', 'tourism' => 'Tourism', 'rural' => 'Rural', 'private_residential' => 'Private Residential',
            ],
        ],
        'land_type' => [
            'label_key' => 'Land Type',
            'type' => 'select',
            'options' => ['rocky' => 'Rocky', 'red_soil' => 'Red Soil', 'sloping' => 'Sloping', 'flat' => 'Flat', 'mountainous' => 'Mountainous'],
        ],
        'services' => [
            'label_key' => 'Services',
            'type' => 'select',
            'options' => [
                'all_connected' => 'All Services Connected (electricity, water, sewage, paved roads)',
                'near_services' => 'Near Services (school, mosque, restaurant, hospital)',
            ],
        ],
        'size' => ['label_key' => 'Land Area (m²)', 'type' => 'range'],
        'price_range' => ['label_key' => 'Price', 'type' => 'price_range'], // price_min, price_max
    ],
];
