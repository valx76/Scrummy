document.addEventListener('DOMContentLoaded', () => {
    if (IS_ROOM_CLOSED) {
        return;
    }

    const choicesCountElement = document.querySelector('#choices_count');

    const choiceEventSource = new EventSource(CHOICE_EVENT_SOURCE_URL);
    choiceEventSource.onmessage = event => {
        const choicesCount = parseInt(event.data);

        choicesCountElement.textContent = choicesCount + (choicesCount > 1 ? ' votes' : ' vote');
    };

    const closeEventSource = new EventSource(CLOSE_EVENT_SOURCE_URL);
    closeEventSource.onmessage = event => {
        window.location.reload();
    };
});
