Feature: Status of API

  Scenario: Get status of API
    When I send a GET request to "/"
    Then the response code is 200
