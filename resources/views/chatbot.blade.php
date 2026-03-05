@extends('layouts.user')

@section('content')
<!-- test -->
<h3 class="mb-4">Asisten Jadwal BookNPlay</h3>

<div class="chat-container card border-0 shadow p-3 p-md-4 bg-transparent">
    <div id="chatBox" class="chat-box"></div>

    <div class="input-group mt-3">
        <input type="text" id="pertanyaan"
               class="form-control"
               placeholder="Contoh: 10-02-2026 14:00">
        <button class="btn btn-primary" onclick="kirim()">Kirim</button>
    </div>
</div>

<style>
.chat-box{
    background:#0f172a;
    height:55vh;
    min-height:360px;
    overflow-y:auto;
    padding:20px;
    border-radius:15px;
}

.user-msg{
    background:#2563eb;
    color:white;
    padding:10px 15px;
    border-radius:15px;
    margin-bottom:10px;
    text-align:right;
    max-width:70%;
    margin-left:auto;
}

.bot-msg{
    background:#1e293b;
    color:white;
    padding:10px 15px;
    border-radius:15px;
    margin-bottom:10px;
    max-width:70%;
}

.typing{
    font-style:italic;
    opacity:0.7;
}
@media (max-width: 768px){
    .user-msg,
    .bot-msg{
        max-width:88%;
    }
}
</style>

<script>
function kirim(){

    let input = document.getElementById('pertanyaan');
    let text = input.value.trim();
    let chatBox = document.getElementById('chatBox');

    if(!text) return;

    chatBox.innerHTML += `<div class="user-msg">${text}</div>`;
    input.value = "";

    // typing indicator
    chatBox.innerHTML += `<div class="bot-msg typing" id="typing">Mengetik...</div>`;
    chatBox.scrollTop = chatBox.scrollHeight;

    fetch("{{ route('chatbot.proses') }}",{
        method:"POST",
        headers:{
            "Content-Type":"application/json",
            "X-CSRF-TOKEN":"{{ csrf_token() }}"
        },
        body:JSON.stringify({pertanyaan:text})
    })
    .then(res=>res.json())
    .then(data=>{

        document.getElementById('typing').remove();

        let message = `<div class="bot-msg">${data.jawaban}`;

        if(data.status === 'kosong'){
            message += `
            <div class="mt-2">
                <a href="/booking?tanggal=${data.tanggal}&jam_mulai=${data.jam}"
                   class="btn btn-success btn-sm">
                   Booking Sekarang
                </a>
            </div>`;
        }

        message += `</div>`;

        chatBox.innerHTML += message;
        chatBox.scrollTop = chatBox.scrollHeight;
    });
}

document.getElementById('pertanyaan').addEventListener('keydown', function(e){
    if(e.key === 'Enter'){
        e.preventDefault();
        kirim();
    }
});
</script>

@endsection
