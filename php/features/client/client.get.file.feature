Feature: Get client file controller tests

  Scenario: User get file
    Given there exist user with login "example@user.com" and password "abc123"
    And there exist a client like
    """
    {
      "id": "example-client-1",
      "firstname": "Jan",
      "lastname": "Kowalski",
      "files": [
        {
          "id": "example-client-file-1",
          "name": "teddy-bear.jpg",
          "path": "tests/Common/File/teddy-bear.jpg"
        }
      ]
    }
    """
    When I open "GET" page "/api/v1/user/clients/example-client-1/files/example-client-file-1" as logged user
    Then the response with code "200" should be received

  Scenario: User tries to get clients but he isn't logged
    When I open "GET" page "/api/v1/user/clients/example-client-1/files/example-client-file-1"
    Then the response with code "401" should be received

#  Scenario: User tries to