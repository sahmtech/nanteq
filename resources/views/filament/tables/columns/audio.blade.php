<div>
    <audio controls style="width:275px; height:35px">
        <source src="{{ url('audio', $getRecord()->id)  }}" type="audio/mpeg">
        <source src="{{ url('audio', $getRecord()->id)  }}" type="audio/ogg">
        <source src="{{ url('audio', $getRecord()->id)  }}" type="audio/wav">
    </audio>
</div>
