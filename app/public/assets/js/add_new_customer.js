function openForm(event){
    event.preventDefault();
    //console.log('openForm');
    document.getElementById("add-new-customer-form").hidden=false;
    document.getElementById("new-form-button").hidden=true;
}
function cancelForm(event){
    event.preventDefault();
    //console.log('cancelForm');
    document.getElementById("add-new-customer-form").hidden=true;
    document.getElementById("new-form-button").hidden=false;
}
function addNewCustomer(event){
    event.preventDefault();
    //console.log('addNewCustomer');
    console.log(document.getElementById("firstName").value);
    console.log(document.getElementById("lastName").value);
    console.log(document.getElementById("email").value);
    console.log(document.getElementById("phone").value);

    const user = {
        firstName: document.getElementById("firstName").value,
        lastName : document.getElementById("lastName").value,
        email : document.getElementById("email").value,
        phone : document.getElementById("phone").value,
        password : document.getElementById("password").value,
        salonId : null
    };
    fetch('/users/addCustomerWithJS', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', },
        body: JSON.stringify(user)
    }).then(result => result.json())
        .then((data) => {
            updateCustomersList(event);
            cancelForm(event);
        }).catch(err => console.error(err));
}

function updateCustomersList(){
    fetch('/users/getCustomerWithJS')
        .then(result => result.json())
        .then((data) => {
            const selectCustomer = document.getElementById("customerId");
            selectCustomer.innerHTML = '';
            const zeroOption = document.createElement("option");
            zeroOption.appendChild(document.createTextNode("-- select customer --"));
            selectCustomer.appendChild(zeroOption);
            data.forEach(user => {
                const userOption = document.createElement("option");
                userOption.value = user.id;
                userOption.appendChild(document.createTextNode(user.name));
                selectCustomer.appendChild(userOption);
            })
        }).catch(err => console.error(err));
}

document.getElementById("new-form-button").addEventListener("click", openForm);
document.getElementById("add-new-customer").addEventListener("click", addNewCustomer);
document.getElementById("cancel-new-customer").addEventListener("click", cancelForm);