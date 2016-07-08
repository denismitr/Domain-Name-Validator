<?php

namespace Denismitr\Validators;

class RealDomainValidator
{
    /**
     * Laravel validation callback
     * @param  $attribute
     * @param  $value
     * @param  $parameters
     * @return [Boolean]
     */
    public function validationCallback($attribute, $value, $parameters)
    {
        return $this->isValidDomain($value);
    }


    /**
     * Validate given domain
     *
     * @param  [String]  $domain
     * @return boolean
     */
    public function isValidDomain($domain)
    {
        $domain = $this->filterHttpPrefix($domain);

        // Basic domain test
        if ( ! $this->passesBasicDomainTest($domain)) {
            return false;
        }

        return $this->checkTheDomainValidity($domain);
    }


    /**
     * Execute some basic domain name validation without sending out DNS requests
     * @param  [String] $domain
     * @return [Boolean]
     */
    protected function passesBasicDomainTest($domain)
    {
        if (filter_var('http://' . $domain, FILTER_VALIDATE_URL) === false) {
            return false;
        }

        return true;
    }

    /**
     * Filterout the http or https prefixes
     * @param  [String] $domain
     * @return [String]
     */
    protected function filterHttpPrefix($domain)
    {
        $domain = idn_to_ascii($domain);

        if (strpos($domain, 'http://') === 0) {
            $domain = str_replace('http://', '', $domain);
        }

        if (strpos($domain, 'https://') === 0) {
            $domain = str_replace('https://', '', $domain);
        }

        return $domain;
    }

    /**
     * Check if the domain dns recors are valid
     * @param   $domain
     * @return  [Boolean]
     */
    protected function checkTheDomainValidity($domain)
    {
        $domainRecords = @dns_get_record($domain);

        if ( ! empty($domainRecords) && is_array($domainRecords)) {
            foreach ($domainRecords as $record) {
                //Record array must contain the ip field
                if ( isset($record['ip']) ) {

                    if ($record['host'] === $domain &&
                            filter_var($record['ip'], FILTER_VALIDATE_IP) !== false &&
                            $record['type'] === 'A'
                        ) {
                            return true;
                    }

                }
            }
        }

        return false;
    }

}