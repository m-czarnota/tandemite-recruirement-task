Feature: Get client file controller tests

  Scenario: User get file successfully
    Given there exist a client like
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
    When I open "GET" page "/api/v1/clients/example-client-1/files/example-client-file-1"
    Then the response with code "200" should be received

#  Scenario: User tries to