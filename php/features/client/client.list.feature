Feature: List client controller tests

  Scenario: User gets clients successfully
    Given there exist user with login "example@user.com" and password "abc123"
    When I open "GET" page "/api/v1/user/clients" as logged user
    Then the response with code "200" should be received

  Scenario: User tries to get clients but he isn't logged
    When I open "GET" page "/api/v1/user/clients"
    Then the response with code "401" should be received