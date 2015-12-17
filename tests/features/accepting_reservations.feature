Feature: accepting reservations
  As a valid user, I can borrow some items to other users using the platform.

  Scenario: Accepting reservation paying a given platform fee.
    Given The application settings 'platform-fee-ratio' is set to '0.25'
    Given I am a valid user named 'Marcel'
    Given There is a 'Hammer' to let, owned by 'Emile'
    And The 'Hammer' is available on '2014-05-27' for 20 euros per day
    When I sign in as Marcel
    And I go to the 'Hammer' page
    And I reserve it on '2014-05-27' for the hole day
    Then I see in the page 'Acompte à payer pour la réservation : 5 €'
    When I click "Confirmer"
    Then I am on the payment page
    And The price to pay is 5 euros

  Scenario: Accepting reservation without platform fee.
    Given The application settings 'platform-fee-ratio' is set to '0'
    Given I am a valid user named 'Marcel'
    Given There is a 'Hammer' to let, owned by 'Emile'
    And The 'Hammer' is available on '2014-05-27' for 20 euros per day
    When I sign in as Marcel
    And I go to the 'Hammer' page
    And I reserve it on '2014-05-27' for the hole day
    Then I dont see in the page 'Acompte à payer pour la réservation'
    When I click 'Confirmer'
    Then I am on 'my reservation' page
    And on my reservation page I can see the 'Hammer' borrowed on '2015-05-27' for the whole day

