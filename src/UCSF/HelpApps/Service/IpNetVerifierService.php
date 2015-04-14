<?php

namespace UCSF\HelpApps\Service;

/**
 * Class IpNetVerifierService
 * @package UCSF\HelpApps\Service
 */
class IpNetVerifierService
{

    const CAMPUS = 'UCSF Campus Network';
    const CAMPUS_DSL_VPN = 'UCSF Campus DSL VPN System';
    const CAMPUS_NORTEL_VPN = 'UCSF Campus Nortel VPN System';
    const CAMPUS_SSL_VPN = 'UCSF Campus SSL VPN System';
    const ITS_SSL_VPN_TEST = 'UCSF UCSF ITS SSL VPN Test System';
    const MED_CENTER = 'UCSF Medical Center';
    const MED_CENTER_CISCO_VPN = 'Medical Center Cisco VPN';
    const MED_CENTER_NETWORK = 'UCSF Medical Center Network';
    const MED_CENTER_WEB_VPN = 'Medical Center Web VPN';
    const MISSION_BAY_CAMPUS = 'UCSF Mission Bay Campus Network';
    const MISSION_BAY_MIXED_HOUSING = 'UCSF Mission Bay Mixed Housing Network';
    const MISSON_BAY_COMMUNITY_CENTER = 'UCSF Mission Bay Community Center Network';
    const PRIVATE_SPACE = 'UCSF Network - Private Space';
    const QB3_AUTHN_WIFI = 'UCSF QB3 Authenticated Wireless Network';
    const QB3_OPEN_WIFI = 'UCSF QB3 Open Wireless Wireless Network';

    /**
     * Resolves and returns a location for a given IPv4 address.
     * @param string $ipAddress The given IP address.
     * @return string|bool The location that the given IP address is resolving to, or FALSE is no location can be found.
     */
    public function getLocation($ipAddress)
    {
        // input validation
        // @todo throw an exception here? [ST 2015/04/12]
        if (!filter_var($ipAddress, FILTER_VALIDATE_IP)) {
            return false;
        }

        // tokenize given IP and convert its parts to base-10 integers
        $parts = explode('.', $ipAddress);
        array_walk(
            $parts,
            function (&$v, $k) {
                $v = intval($v, 10);
            }
        );
        list($octet1, $octet2, $octet3, $octet4) = $parts;

        // range check
        // 10.
        if (10 === $octet1) {
            return self::PRIVATE_SPACE;
        }
        // 64.54.
        if ((64 === $octet1) && (54 === $octet2)) {
            if (($octet3 >= 10) && ($octet3 <= 14)) {
                if (10 === $octet3) {
                    return self::MED_CENTER_CISCO_VPN;
                } elseif (13 === $octet3) {
                    return self::MED_CENTER_WEB_VPN;
                } else {
                    return self::MED_CENTER;
                }
            } elseif ((0 <= $octet3) && (127 >= $octet3)) {
                return self::MED_CENTER_NETWORK;
            } elseif ((249 <= $octet3) && (251 >= $octet3)) {
                return self::CAMPUS_NORTEL_VPN;
            } elseif ((128 <= $octet3) && (255 >= $octet3)) {
                return self::CAMPUS;
            } else {
                return self::MED_CENTER_NETWORK;
            }
        }
        // 128.218.
        if ((128 === $octet1) && (218 === $octet2)) {
            if (28 === $octet3) {
                if ((192 <= $octet4) && (223 >= $octet4)) {
                    return self::ITS_SSL_VPN_TEST;
                }
            } elseif (174 === $octet3) {
                if ((37 <= $octet4) && (61 >= $octet4) || ((69 <= $octet4) && (93 >= $octet4))) {
                    return self::CAMPUS_NORTEL_VPN;
                }
            } else {
                return self::CAMPUS;
            }
        }
        // 169.230
        if ((169 === $octet1) && (230 === $octet2)) {
            if ((100 <= $octet3) && (109 >= $octet3)) {
                return self::MISSION_BAY_MIXED_HOUSING;
            } elseif ((110 <= $octet3) && (120 >= octet3)) {
                return self::MISSON_BAY_COMMUNITY_CENTER;
            } elseif ((226 <= $octet3) && (227 >= $octet3)) {
                return self::QB3_AUTHN_WIFI;
            } elseif ((228 <= $octet3) && (127 >= $octet4)) {
                return self::QB3_OPEN_WIFI;
            } elseif ((240 <= $octet3) && (243 >= $octet3)) {
                return self::CAMPUS_SSL_VPN;
            } elseif ((244 <= $octet3) && (247 >= $octet3)) {
                return self::CAMPUS_DSL_VPN;
            } else {
                return self::MISSION_BAY_CAMPUS;
            }
        }

        // not in range.
        return false;
    }
}
