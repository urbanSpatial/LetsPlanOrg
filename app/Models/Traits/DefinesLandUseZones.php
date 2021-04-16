<?php

namespace App\Models\Traits;

trait DefinesLandUseZones
{
    public static $residential = [
        'RSD1', 'RSD2', 'RSD3', 'RMX1', 'RMX2', 'RSA', 'RSA1', 'RSA2', 'RSA3', 'RS3', 'RSA4', 'RSA5', 'RTA1',
    ];
    public static $residentialHigh = [
        'RM1', 'RM2', 'RM3', 'RM4', 'RMX3',
    ];
    public static $industrial = [
        'IRMX', 'ICMX', 'I1', 'I2', 'I3', 'IP',
    ];
    public static $commercial = [
        'CMX1', 'CMX2', 'CMX25'
    ];
    public static $commercialHigh = [
        'CMX3', 'CMX4', 'CMX5'
    ];
    public static $special = [
        'CA1', 'CA2', 'SPINS', 'SPENT', 'SPSTA', 'SPPOP', 'SPPOA', 'SPAIR', '12', 'SC', 'SP', '2002'
    ];
}
