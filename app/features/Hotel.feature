#features/Hotel.feature
Feature:
    Scenario: Try to get availability of hotel that does not exist
        When I send a "POST" request to "/api/v1/hotel/availability" with the body:
        """
        {
            "hotel_id": 9,
            "check_in": "2023-12-09",
            "check_out": "2023-12-15",
        }
        """
        Then the response status code should be 404
    
    Scenario: Try to get availability of hotel with an invalid date format
        When I send a "POST" request to "/api/v1/hotel/availability" with the body:
        """
        {
            "hotel_id": 1,
            "check_in": "abcdefg",
            "check_out": "2023-12-15",
        }
        """
        Then the response status code should be 422

    Scenario: Successful available request
        When I send a "POST" request to "/api/v1/hotel/availability" with the body:
        """
        {
            "hotel_id": 1,
            "check_in": "2023-11-20",
            "check_out": "2023-11-23",
        }
        """
        Then the response status code should be 200

    Scenario: Successful unavailable request
        When I send a "POST" request to "/api/v1/hotel/availability" with the body:
        """
        {
            "hotel_id": 1,
            "check_in": "2023-12-09",
            "check_out": "2023-12-15",
        }
        """
        Then the response status code should be 200