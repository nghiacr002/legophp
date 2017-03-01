<div class="messages">
    {% if flash.hasMessage() %}
    {% for flashMessage in flash.getMessages() %}
    <p class="login-box-msg flash-{{ flashMessage.type}}" rel="{{ flashMessage.element }}">
        {{ flashMessage.message }}
    </p>
    {% endfor %}
    {% endif %}
</div>