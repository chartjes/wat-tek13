Feature: Functionality of Grumpy PHPUnit site 

    Scenario: Purchase link sends to Leanpub
        Given I am on "/index.html"
        When I press "Buy Now Via Leanpub for $29"
        Then I should see "You're buying"
        And I should see "PHPUnit Cookbook"
