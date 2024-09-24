const URL = '../index.php'
const btn = document.querySelector('form')
const email = document.querySelector('#email')
const password = document.querySelector('#password')

btn.addEventListener('submit', async(event)=>{
    event.preventDefault()
    let response = await fetch(URL,{
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body:JSON.stringify({
            method:'signup',
            email:email.value,
            password:password.value
        })
    })
    if(response.ok){
        let data = await response.json();
        console.log(data)
    }
    else{
        console.log('error')
    }
})