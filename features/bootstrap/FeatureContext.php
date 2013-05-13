<?php

use Behat\Behat\Context\BehatContext,
Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Behat\Mink\Driver\GoutteDriver;
use Behat\MinkExtension\Context\MinkContext;

require '../web/vendor/autoload.php';

require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';

/**
 * Features context.
 */
class FeatureContext extends MinkContext 
{
    public $fizzBuzz;
    public $response;
    public $modalElement;

    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        //parent::__construct($parameters);
    }

    /**
     * @Given /^I have a FizzBuzz$/
     */
    public function iHaveAFizzbuzz()
    {
        $this->fizzBuzz = new Grumpy\FizzBuzz();
    }

    /**
     * @When /^I tell it to parse \'([^\']*)\'$/
     */
    public function iTellItToParse($arg1)
    {
        $this->response = $this->fizzBuzz->parse($arg1);
    }

    /**
     * @Then /^I should get back \'([^\']*)\'$/
     */
    public function iShouldGetBack($arg1)
    {
        assertEquals($this->response, $arg1);
    }

    /**
     * @Then /^I should see the modal "([^"]*)"$/
     */
    public function iShouldSeeTheModal($title)
    {
        $session = $this->getSession();
        $page = $session->getPage();
        $session->wait(1000);
        $cssSelector = 'html body div.container ';
        $cssSelector .= 'div.row div.span9 section#modals ';
        $cssSelector .= 'div#myModal.modal div.modal-header ';
        $cssSelector .= 'h3#myModalLabel';
        $this->assertElementContainsText(
          $cssSelector, 
          $title
        );
        assertTrue($page->find('css', $cssSelector)
          ->isVisible()
        );
    }

    /**
     * @When /^I press the modal button "([^"]*)"$/
     */
    public function iPressTheModalButton($text)
    {
        $page = $this->getSession()->getPage();
        $cssSelector = 'html body div.container div.row ';
        $cssSelector .= 'div.span9 section#modals ';
        $cssSelector .= 'div.bs-docs-example a.btn';
        $elements = $page->findAll('css', $cssSelector);
        $foundModal = false;
        
        foreach ($elements as $element) {
            if (stristr($element->getText(), $text)) {
                $modalElement = $element;
                $foundModal = true;
            }
        }

        assertTrue($foundModal);
        $modalElement->click();
    }
}
