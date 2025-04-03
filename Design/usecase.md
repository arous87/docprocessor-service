@startuml
    
    participant "Client" as client
    participant "DocProcessingService" as service
    
    note over client: Client Authenticated

    group Save/Update Document
        client -> service: Save document
        service -> service: Generate tags
        service -> client: Suggested tags
        client -> service: Update document tags
    end group

    group Get recent tags
        client -> service: Get 5 most used tags for given User
        service -> client: Return Tag list
    end group

    group Get recent Documents
        client -> service: Get 10 most recent documents for given User
        service -> client: Return Document list
    end group

    group Search by tag
        client -> service: Get all documents for given Tag and User
        service -> client: Return Document list
    end group
    
    
    @enduml