Feature: List client controller tests

  Scenario: User gets clients successfully
    When I open "GET" page "/api/v1/clients"
    Then the response with code "200" should be received