$baseUrl = "http://127.0.0.1:8000"
$adminEmail = "admin@homeminister.com"
$adminPassword = "password"
$userAgent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64)"

function Print-Result {
    param($TestName, $Url, $Status, $Passed)
    if ($Passed) {
        Write-Host "[PASS] $TestName - $Url ($Status)" -ForegroundColor Green
    } else {
        Write-Host "[FAIL] $TestName - $Url ($Status)" -ForegroundColor Red
    }
}

# 1. Test Public API: Mobile Check
try {
    $url = "$baseUrl/api/check-mobile?mobile=9999999999"
    $res = Invoke-WebRequest -Uri $url -Method Get -UserAgent $userAgent
    $json = $res.Content | ConvertFrom-Json
    Print-Result "Public API: Check Mobile" $url $res.StatusCode ($res.StatusCode -eq 200)
} catch {
    Print-Result "Public API: Check Mobile" $url "Error" $false
    Write-Host $_.Exception.Message
}

# 2. Login Flow (Get CSRF -> Login)
Write-Host "`n--- Authenticating as Admin ---"
try {
    # Get Login Page for CSRF
    $loginPageReq = Invoke-WebRequest -Uri "$baseUrl/login" -SessionVariable session -UserAgent $userAgent
    
    # Extract CSRF Token
    $csrfToken = "Unknown"
    if ($loginPageReq.Content -match 'name="_token" value="([^"]+)"') {
        $csrfToken = $matches[1]
    }
    
    if ($csrfToken -eq "Unknown") {
        Write-Host "Could not find CSRF token." -ForegroundColor Red
        exit
    }

    # Post Login
    $body = @{
        "_token" = $csrfToken
        "email" = $adminEmail
        "password" = $adminPassword
    }
    
    $loginReq = Invoke-WebRequest -Uri "$baseUrl/login" -Method Post -Body $body -WebSession $session -UserAgent $userAgent -MaximumRedirection 0 -ErrorAction SilentlyContinue
    
    # Check if redirect (302) or success. Laravel redirects to /dashboard on success.
    if ($loginReq.StatusCode -eq 302 -or $loginReq.StatusCode -eq 200) {
        Print-Result "Admin Login" "$baseUrl/login" $loginReq.StatusCode $true
    } else {
        Print-Result "Admin Login" "$baseUrl/login" $loginReq.StatusCode $false
        exit
    }

} catch {
    # 302 is often caught as error in older PS versions, but we handle it.
     if ($_.Exception.Response.StatusCode.value__ -eq 302) {
        Print-Result "Admin Login" "$baseUrl/login" "302 Redirect" $true
    } else {
        Print-Result "Admin Login" "$baseUrl/login" "Error" $false
        Write-Host $_.Exception.Message
        exit
    }
}

# 3. Test Protected Routes (Using Session)
$endpoints = @(
    @{"Name"="Dashboard"; "Url"="/dashboard"; "Check"="Total Registered"},
    @{"Name"="Participants List"; "Url"="/participants"; "Check"="Participant Directory"},
    @{"Name"="Pending List"; "Url"="/participants/pending"; "Check"="Pending Approvals"},
    @{"Name"="Winning Gifts"; "Url"="/winning-gifts"; "Check"="Winning Gifts Module"},
    @{"Name"="Winners API (Search)"; "Url"="/api/winners/search?query=test"; "Check"=""},
    @{"Name"="User Management"; "Url"="/admin/users"; "Check"="Users List"}
)

foreach ($ep in $endpoints) {
    $fullUrl = $baseUrl + $ep.Url
    try {
        $res = Invoke-WebRequest -Uri $fullUrl -WebSession $session -Method Get -UserAgent $userAgent
        
        $passed = $res.StatusCode -eq 200
        if ($ep.Check -ne "") {
            $contentCheck = $res.Content -match $ep.Check
            $passed = $passed -and $contentCheck
        }

        Print-Result $ep.Name $fullUrl $res.StatusCode $passed
    } catch {
        Print-Result $ep.Name $fullUrl "Error" $false
        Write-Host $_.Exception.Message
    }
}

# 4. Test Search Filters (Participants)
Write-Host "`n--- Testing Filters ---"
try {
    $url = "$baseUrl/participants?search=Test"
    $res = Invoke-WebRequest -Uri $url -WebSession $session -Method Get -UserAgent $userAgent
    Print-Result "Filter: Search Participants" $url $res.StatusCode ($res.StatusCode -eq 200)
} catch {
     Print-Result "Filter: Search Participants" $url "Error" $false
}

try {
    $url = "$baseUrl/participants/pending?voter_filter=1"
    $res = Invoke-WebRequest -Uri $url -WebSession $session -Method Get -UserAgent $userAgent
    Print-Result "Filter: Pending Voter ID" $url $res.StatusCode ($res.StatusCode -eq 200)
} catch {
     Print-Result "Filter: Pending Voter ID" $url "Error" $false
}

try {
    $url = "$baseUrl/participants/pending?search=Test"
    $res = Invoke-WebRequest -Uri $url -WebSession $session -Method Get -UserAgent $userAgent
    Print-Result "Filter: Search Pending" $url $res.StatusCode ($res.StatusCode -eq 200)
} catch {
     Print-Result "Filter: Search Pending" $url "Error" $false
}
