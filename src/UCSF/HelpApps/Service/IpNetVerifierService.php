<?php

namespace UCSF\HelpApps\Service;

class IpNetVerifierService
{
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
            return 'UCSF Network - Private Space';
        }
        // 64.54.
        if ((64 === $octet1) && (54 === $octet2)) {
            if (($octet3 >= 10) && ($octet3 <= 14)) {
                if (10 === $octet3) {
                    return 'Medical Center Cisco VPN';
                } elseif (13 === $octet3) {
                    return 'Medical Center Web VPN';
                } else {
                    return 'UCSF Medical Center';
                }
            } elseif ((0 <= $octet3) && (127 >= $octet3)) {
                return 'UCSF Medical Center Network';
            } elseif ((249 <= $octet3) && (251 >= $octet3)) {
                return 'UCSF Campus Nortel VPN System';
            } elseif ((128 <= $octet3) && (255 >= $octet3)) {
                return 'UCSF Campus Network';
            } else {
                return 'UCSF Medical Center Network';
            }
        }
        // 128.218.
        if ((128 === $octet1) && (218 === $octet2)) {
            if (28 === $octet3) {
                if ((192 <= $octet4) && (223 >= $octet4)) {
                    return 'UCSF ITS SSL VPN Test System';
                }
            } elseif (174 === $octet3) {
                if ((37 <= $octet4) && (61 >= $octet4) || ((69 <= $octet4) && (93 >= $octet4))) {
                    return 'UCSF Campus Nortel VPN System';
                }
            } else {
                return 'UCSF Campus Network';
            }
        }
        // 169.230
        if ((169 === $octet1) && (230 === $octet2)) {
            if ((100 <= $octet3) && (109 >= $octet3)) {
                return 'UCSF Mission Bay Mixed Housing Network';
            } elseif ((110 <= $octet3) && (120 >= octet3)) {
                return 'UCSF Mission Bay Community Center Network';
            } elseif ((226 <= $octet3) && (227 >= $octet3)) {
                return 'UCSF QB3 Authenitcated Wireless Network';
            } elseif ((228 <= $octet3) && (127 >= $octet4)) {
                return 'UCSF QB3 Open Wireless Wireless Network';
            } elseif ((240 <= $octet3) && (243 >= $octet3)) {
                return 'UCSF Campus SSL VPN System';
            } elseif ((244 <= $octet3) && (247 >= $octet3)) {
                return 'UCSF Campus DSL VPN System';
            } else {
                return 'UCSF Mission Bay Campus Network';
            }
        }

        // not in range.
        return false;
    }
}
