TODO #1  

- add a dashboard
- implement the logout function
- fix some ui elements 


SQL For the Making of Vechicle Tables

```SQL

CREATE TABLE IF NOT EXISTS vehicles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    county VARCHAR(255) NOT NULL,
    state VARCHAR(255) NOT NULL,
    vehicle_primary_use VARCHAR(255) NOT NULL,
    electric_vehicle_ev_total INT NOT NULL,
    non_electric_vehicles INT NOT NULL,
    total_vehicles INT NOT NULL,
    percent_electric_vehicles DECIMAL(5,2) NOT NULL
);



```

