<?php
    require 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="icon.png">
        
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="fonts/icomoon/style.css">

        <link rel="stylesheet" href="css/owl.carousel.min.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css">

        <title>Lizenzen von kajado GmbH</title>
    </head>
    
    <body>
        <nav style="display: flex;">
            <div><a href="#licenses">Lizenzen</a><a class="add" onclick="addLicense()">+</a></div>
            <div><a href="#providers">Anbietern</a><a class="add" onclick="addOther(1)">+</a></div>
            <div><a href="#clients">Kunden</a><a class="add" onclick="addOther(2)">+</a></div>
        </nav>

        <div class="content">
            <div class="container">
                <h2 id="licenses" style="margin-top: 5em;">Lizenzen</h2>
                <div class="table-responsive custom-table-responsive">
                    <table class="table custom-table">
                        <thead>
                            <tr>  
                                <th scope="col">
                                    <label class="control control--checkbox" style="opacity: 0;">
                                        <input type="checkbox">
                                        <div class="control__indicator"></div>
                                    </label>
                                </th>

                                <th scope="col">Name</th>
                                <th scope="col">Beschreibung</th>
                                <th scope="col">Preis</th>
                                <th scope="col">Erneuerung</th>
                                <th scope="col">Zahlung per</th>
                                <th scope="col">Beginn der Laufzeit</th>
                                <th scope="col">Voraussichtliches Ende</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <?php
                                $licenses = $connection -> query("SELECT * FROM `licenses`");
                                $licenseIds = [];
                                $licenseNames = [];

                                while ($license = $licenses -> fetch_assoc()) {
                                    $renovationMonth = $license['renovation-month'];
                                    array_push($licenseIds, $license['id']);
                                    array_push($licenseNames, $license['name']);

                                    if ($renovationMonth == 0)
                                        $renovation = 'einmalig';
                                    
                                    else {
                                        if ($renovationMonth == 1)
                                            $renovation = 'monatlich';
                                        else if ($renovationMonth == 3)
                                            $renovation = 'vierteljährlich';
                                        else if ($renovationMonth == 12)
                                            $renovation = 'jährlich';
                                        else
                                            $renovation = 'nach ' . $renovationMonth . ' Monaten';

                                        $runtimeStartArray = explode('.', $license['runtime-start']);
                                        $runtimeStart = (int) $runtimeStartArray[1] + (int) $runtimeStartArray[2] * 12;
                                        $today = (int) date('m') + (int) date('Y') * 12;
                                        $runtimeEnd = $runtimeStart + $today - $runtimeStart + + $renovationMonth - ceil(($today - $runtimeStart) % $renovationMonth) - 1;
                                        $runtimeEnd = (string) $runtimeStartArray[0] . '.' . (string) $runtimeEnd % 12 + 1 . '.' . (string) floor($runtimeEnd / 12);
                                    }

                                    ?>
                                    
                                    <tr scope="row">
                                        <th scope="row">
                                            <label class="control control--checkbox">
                                                <input type="checkbox" id="<?= $license['id'] ?>" class="licenses" onchange="edit_remove(this)">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </th>
                                        
                                        <td><?= $license['name'] ?></td>
                                        <td><?= $license['description'] ?></td>
                                        <td><?= $license['price'] ?> €</td>
                                        <td id="<?= $renovationMonth ?>"><?= $renovation ?></td>
                                        <td><?= $license['payment'] ?></td>
                                        <td><?= $license['runtime-start'] ?></td>
                                        <td><?= $runtimeEnd ?></td>
                                    </tr>
                                    <tr class="spacer"><td colspan="100"></td></tr>
                                    
                                    <?php
                                }

                                $licenseIds = implode('|', $licenseIds);
                                $licenseNames = implode('|', $licenseNames);
                            ?>
                        </tbody>
                    </table>
                </div>

                <h2 id="providers">Anbietern</h2>
                <div class="table-responsive custom-table-responsive">
                    <table class="table custom-table">
                        <thead>
                            <tr>  
                                <th scope="col">
                                    <label class="control control--checkbox" style="opacity: 0;">
                                        <input type="checkbox">
                                        <div class="control__indicator"></div>
                                    </label>
                                </th>

                                <th scope="col">Name</th>
                                <th scope="col">Beschreibung</th>
                                <th scope="col">Domain</th>
                                <th scope="col">Link</th>
                                <th scope="col">Namen der Lizenzen</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <?php
                                $providers = $connection -> query("SELECT * FROM `providers`");

                                while ($provider = $providers -> fetch_assoc()) {
                                    ?>
                                    
                                    <tr scope="row">
                                        <th scope="row">
                                            <label class="control control--checkbox">
                                                <input type="checkbox" id="<?= $provider['id'] ?>" class="providers" onchange="edit_remove(this)">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </th>
                                        
                                        <td><?= $provider['name'] ?></td>
                                        <td><?= $provider['description'] ?></td>

                                        <td><a href="<?= $provider['domain'] ?>" style="color: #fff;"><?= $provider['domain'] ?></a></td>
                                        <td><a href="<?= $provider['domain'] ?>" style="color: #fff;"><?= $provider['link'] ?></a></td>

                                        <td><?php
                                            $providerLicenseIds = json_decode($provider['license-id']);
                                            foreach ($providerLicenseIds as $providerLicenseId) {
                                                $license = $connection -> query("SELECT * FROM `licenses` WHERE id = $providerLicenseId") -> fetch_assoc();
                                                if (isset($license['name']))
                                                    echo $license['name'] . ';&nbsp;';                                            }
                                        ?></td>
                                    </tr>
                                    <tr class="spacer"><td colspan="100"></td></tr>
                                    
                                    <?php
                                }
                            ?>
                        </tbody>
                    </table>
                </div>

                <h2 id="clients">Kunden</h2>
                <div class="table-responsive custom-table-responsive">
                    <table class="table custom-table">
                        <thead>
                            <tr>  
                                <th scope="col">
                                    <label class="control control--checkbox" style="opacity: 0;">
                                        <input type="checkbox">
                                        <div class="control__indicator"></div>
                                    </label>
                                </th>

                                <th scope="col">Name</th>
                                <th scope="col">Kundenkürzel</th>
                                <th scope="col">Namen der Lizenzen</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <?php
                                $clients = $connection -> query("SELECT * FROM `clients`");

                                while ($client = $clients -> fetch_assoc()) {
                                    ?>
                                    
                                    <tr scope="row">
                                        <th scope="row">
                                            <label class="control control--checkbox">
                                                <input type="checkbox" id="<?= $client['id'] ?>" class="clients" onchange="edit_remove(this)">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </th>
                                        
                                        <td><?= $client['name'] ?></td>
                                        <td><?= $client['short-name'] ?></td>

                                        <td><?php
                                            $clientLicenseIds = json_decode($client['license-id']);
                                            foreach ($clientLicenseIds as $clientLicenseId) {
                                                $license = $connection -> query("SELECT * FROM `licenses` WHERE id = $clientLicenseId") -> fetch_assoc();
                                                if (isset($license['name']))
                                                    echo $license['name'] . ';&nbsp;';
                                            }
                                        ?></td>
                                    </tr>
                                    <tr class="spacer"><td colspan="100"></td></tr>
                                    
                                    <?php
                                }
                            ?>
                        </tbody>
                    </table>
                </div>

                <h2>Datum: <?= date('d.m.Y') ?></h2>
            </div>
        </div>

        <script src="js/jquery-3.3.1.min.js"></script>
        <script src="js/popper.min.js"></script>
        <script src="js/bootstrap.min.js"></script>

        <script>
            navFixed = true

            function addLicense() {
                const rows = document.querySelectorAll('table.table.custom-table')[0].querySelector('tbody'),
                      nav = document.querySelector('nav')

                rows.innerHTML += `<tr scope="row">
    <th scope="row"></th>
    <td><input class="name" placeholder="Name"></td>
    <td><input class="description" placeholder="Beschreibung"></td>
    <td><input type="number" class="price" placeholder="Preis (in €)"></td>
    <td><input type="number" class="renovation-month" placeholder="Erneuerung (Monate)"></td>
    <td><input class="payment" placeholder="Zahlung per"></td>
    <td><input type="date" class="runtime-start" placeholder="Start der Laufzeit"></td>
</tr>
<tr class="spacer"><td colspan="100"></td></tr>`
                
                nav.querySelectorAll('div')[0].innerHTML = '<a href="#licenses">Lizenzen</a><button onclick="location.reload()">Abbrechen</button><button onclick="add(\'licenses\', \'add\')">Fertig</button>'
                document.querySelectorAll('.add').forEach(add => add.onclick = '')
                navFixed = false
                nav.style.display = 'flex'
            }

            function addOther(type) {
                const table = document.querySelectorAll('.table-responsive.custom-table-responsive')[type],
                      rows = table.querySelector('tbody'),
                      nav = document.querySelector('nav')

                if (type == 1) {
                    rows.innerHTML += `<tr scope="row">
    <th scope="row"></th>
    <td><input class="name" placeholder="Name"></td>
    <td><input class="description" placeholder="Beschreibung"></td>
    <td><input class="domain" placeholder="Domain"></td>
    <td><input class="link" placeholder="Link"></td>
    <td>↓ Wähle unten die Lizenzen ↓</td>
</tr>`

                    nav.querySelectorAll('div')[1].innerHTML = '<a href="#providers">Anbietern</a><button onclick="location.reload()">Abbrechen</button><button onclick="add(\'providers\', \'add\')">Fertig</button>'
                }

                else if (type == 2) {
                    rows.innerHTML += `<tr scope="row">
    <th scope="row"></th>
    <td><input class="name" placeholder="Name"></td>
    <td><input class="short-name" placeholder="Kundenkürzel"></td>
    <td>↓ Wähle unten die Lizenzen ↓</td>
</tr>`
                    
                    nav.querySelectorAll('div')[2].innerHTML = '<a href="#clients">Kunden</a><button onclick="location.reload()">Abbrechen</button><button onclick="add(\'clients\', \'add\')">Fertig</button>'
                }

                licenseArray = document.createElement('div')
                licenseArray.className = 'license-array'
                licenseIds = '<?= $licenseIds ?>'.split('|')
                licenseNames = '<?= $licenseNames ?>'.split('|')

                for (i = 0; i < licenseIds.length; i++)
                    licenseArray.innerHTML += '<div class="license"><input type="checkbox" id=' + licenseIds[i] + '>' + licenseNames[i] + '</div>'
                table.appendChild(licenseArray)

                document.querySelectorAll('.add').forEach(add => add.onclick = '')
                navFixed = false
                nav.style.display = 'flex'
            }

            function add(type, action, id = '') {
                const editRemove = document.querySelector('.edit_remove')

                if (type == 'providers' || type == 'clients') {
                    licenseId = []
                    document.querySelectorAll('.license input').forEach(license => {
                        if (license.checked)
                            licenseId.push(parseInt(license.id))
                    })
                    licenseId = JSON.stringify(licenseId)
                }

                else
                    licenseId = ''

                if (editRemove)
                    editRemove.remove()

                $.ajax({
                    url: action + '.php',
                    type: 'post',
                    data: {
                        'type': type,
                        'name': document.querySelector('.name') ? document.querySelector('.name').value : '',
                        'short-name': document.querySelector('.short-name') ? document.querySelector('.short-name').value : '',
                        'description': document.querySelector('.description') ? document.querySelector('.description').value : '',
                        'price': document.querySelector('.price') ? document.querySelector('.price').value : '',
                        'renovation-month': document.querySelector('.renovation-month') ? document.querySelector('.renovation-month').value : '',
                        'payment': document.querySelector('.payment') ? document.querySelector('.payment').value : '',
                        'runtime-start': document.querySelector('.runtime-start') ? document.querySelector('.runtime-start').value : '',
                        'domain': document.querySelector('.domain') ? document.querySelector('.domain').value : '',
                        'link': document.querySelector('.link') ? document.querySelector('.link').value : '',
                        'license-id': licenseId,
                        'id': id
                    },
                    success: () => location.reload()
                })
            }

            

            function edit_remove(element) {
                const nav = document.querySelector('nav'),
                      editRemove = document.querySelector('.edit_remove')
                checked = false

                document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                    if ((checkbox.id != element.id || checkbox.className != element.className) && checkbox.checked)
                        checkbox.checked = false
                    if (checkbox.checked)
                        checked = true
                })

                if (editRemove)
                    editRemove.remove()

                if (checked) {
                    nav.innerHTML += `<div class="edit_remove" bis_skin_checked="1">
    <button onclick="edit('${element.className}', '${element.id}')">Bearbeiten</button>
    <button onclick="remove('${element.className}', '${element.id}')">Löschen</button>
</div>`
                    navFixed = false
                    nav.style.display = 'flex'
                }

                else {
                    navFixed = true
                    if (window.scrollY == 0)
                        nav.style.display = 'flex'
                    else
                        nav.style.display = 'none'
                }
            }

            function edit(type, id) {
                document.querySelectorAll('.' + type).forEach(checkbox => {
                    if (checkbox.id == id)
                        row = checkbox.parentNode.parentNode.parentNode
                })
                const td = row.querySelectorAll('td')

                if (type == 'licenses') {
                    date = td[5].innerText.split('.')
                    row.innerHTML = `<th scope="row"></th>
<td><input class="name" placeholder="Name" value="${td[0].innerText}"></td>
<td><input class="description" placeholder="Beschreibung" value="${td[1].innerText}"></td>
<td><input type="number" class="price" placeholder="Preis (in €)" value="${td[2].innerText.split(' ')[0]}"></td>
<td><input type="number" class="renovation-month" placeholder="Erneuerung (Monate)" value="${td[3].id}"></td>
<td><input class="payment" placeholder="Zahlung per" value="${td[4].innerText}"></td>
<td><input type="date" class="runtime-start" placeholder="Start der Laufzeit" value="${date[2] + '-' + date[1] + '-' + date[0]}"></td>`
                }

                else if (type == 'providers') {
                    licenses = row.querySelectorAll('td')[4].innerText.split(';\xa0')
                    row.innerHTML = `<th scope="row"></th>
<td><input class="name" placeholder="Name" value="${td[0].innerText}"></td>
<td><input class="description" placeholder="Beschreibung" value="${td[1].innerText}"></td>
<td><input class="domain" placeholder="Domain" value="${td[2].innerText}"></td>
<td><input class="link" placeholder="Link" value="${td[3].innerText}"></td>
<td>↓ Wähle unten die Lizenzen ↓</td>`
                }

                else if (type == 'clients') {
                    licenses = row.querySelectorAll('td')[2].innerText.split(';\xa0')
                    row.innerHTML = `<th scope="row"></th>
<td><input class="name" placeholder="Name" value="${td[0].innerText}"></td>
<td><input class="short-name" placeholder="Kundenkürzel" value="${td[1].innerText}"></td>
<td>↓ Wähle unten die Lizenzen ↓</td>`
                }

                if (type == 'providers' || type == 'clients') {
                    licenseArray = document.createElement('div')
                    licenseArray.className = 'license-array'
                    licenseIds = '<?= $licenseIds ?>'.split('|')
                    licenseNames = '<?= $licenseNames ?>'.split('|')
                    console.log(licenses)

                    for (i = 0; i < licenseIds.length; i++) {
                        checked = ''
                        if (licenses.includes(licenseNames[i])) {
                            checked = 'checked'
                        }
                        licenseArray.innerHTML += '<div class="license"><input type="checkbox" id=' + licenseIds[i] + ' ' + checked + '>' + licenseNames[i] + '</div>'
                    }

                    row.parentNode.parentNode.parentNode.appendChild(licenseArray)
                }

                document.querySelectorAll('.add').forEach(add => add.onclick = '')
                document.querySelector('.edit_remove').innerHTML = `<button onclick="location.reload()">Abbrechen</button><button onclick="add('${type}', 'edit', '${id}')">Fertig</button>`
            }

            function remove(type, id) {
                $.ajax({
                    url: 'remove.php',
                    type: 'post',
                    data: {
                        'type': type,
                        'id': id
                    },
                    success: () => location.reload()
                })
            }

            window.addEventListener('scroll', () => {
                if (window.scrollY == 0)
                    document.querySelector('nav').style.display = 'flex'
                else if (navFixed)
                    document.querySelector('nav').style.display = 'none'
            })

            document.addEventListener('mousemove', e => {
                if (window.scrollY > 0) {
                    if (e.clientY <= 80)
                        document.querySelector('nav').style.display = 'flex'
                    else if (navFixed)
                        document.querySelector('nav').style.display = 'none'
                }
            })
        </script>
    </body>
</html>