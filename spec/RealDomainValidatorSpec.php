<?php

namespace spec\Denismitr\Validators;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RealDomainValidatorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Denismitr\Validators\RealDomainValidator');
    }

    //Confirm correct urls
    function it_can_recognize_a_valid_domain_name_without_http_prefix()
    {
        $this->isValidDomain('google.com')->shouldReturn(true);
    }

    function it_can_recognize_a_valid_domain_name_with_a_http_prefix()
    {
        $this->isValidDomain('http://google.com')->shouldReturn(true);
    }

    function it_can_recognize_a_valid_domain_name_with_www_and_a_http_prefix()
    {
        $this->isValidDomain('http://www.google.com')->shouldReturn(true);
    }

    function it_can_recognize_a_valid_domain_name_with_a_https_prefix()
    {
        $this->isValidDomain('https://thecollection.ru')->shouldReturn(true);
    }

    //Laravel specific
    function it_can_serve_as_an_alternative_laravel_callback_validator()
    {
        $this->validationCallback(null, 'yandex.ru', null)->shouldReturn(true);
    }

    //Now wrong urls
    function it_can_detect_an_invalid_domain_name()
    {
        $this->isValidDomain('http://gibberishh.spazzatura.it')->shouldReturn(false);
    }

    function it_can_detect_an_invalid_domain_name_acting_as_laravel_callback()
    {
        $this->validationCallback(null, 'http://gibberishh.spazzatura.it', null)
            ->shouldReturn(false);
    }
}
