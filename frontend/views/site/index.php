<?php
/** @var yii\web\View $this */

$this->title = '3.4 как отображаете нужные данные переводчиков на экране через views (здесь показать применение Vue js)';
?>
<div class="site-index">
    <details id="translators" data-src="http://127.0.0.1:9000/translator/" open>
        <summary>
            <h2>Переводчики</h2>
        </summary>
        
        <div v-if="loading" class="loading">Загрузка...</div>
        <div v-else-if="error" class="error">
            <span>Ошибка:</span>
            <span>{{ error }}</span>
        </div>
        
        <ul v-else class="translator-list">
            <li v-for="translator in translators" :key="translator.translator_id">
                <details
                    @toggle="fetchProfile(translator, $event)"
                    :data-src="'http://127.0.0.1:9000/translator/' + translator.translator_id + '/profile'"
                >
                    <summary>{{ translator.username }}</summary>
                    
                    <div v-if="translator.loading" class="loading">Загрузка профиля...</div>
                    <div v-else-if="translator.error" class="error">Ошибка загрузки профиля</div>
                    
                    <ul v-else-if="translator.profile && translator.profile.length" class="profile-list" data-label="переводы">
                        <li v-for="(translation, index) in translator.profile" :key="index">
                            <details
                                class="profile-list-languages"
                                @toggle="fetchSchedule(translation, $event)"
                                :data-src="'http://127.0.0.1:9000/translator/schedule/' + translator.translator_id + '/' + translation.language1_id + '/' + translation.language2_id"
                            >
                                <summary>
                                    <ul class="profile-list-language-pair">
                                        <li data-delimiter="&rarr;">{{ translation.language1_title }}</li>
                                        <li>{{ translation.language2_title }}</li>
                                    </ul>
                                </summary>
                                <!-- Исправлено: обращаемся к translation.schedule -->
                                <ul
                                    v-if="translation.schedule && translation.schedule.length"
                                    class="profile-list-language-pair-schedule"
                                    data-label="расписание"
                                >
                                    <li
                                        v-for="(scheduleItem, sIndex) in translation.schedule"
                                        :key="sIndex"
                                        :data-is_vikhodnoy="scheduleItem.is_vikhodnoy"
                                    >
                                        {{ scheduleItem.date_at }}
                                    </li>
                                </ul>
                                <div v-else>Нет данных о расписании</div>
                            </details>
                        </li>
                    </ul>
                    
                    <div v-else>Нет данных о переводах</div>
                </details>
            </li>
        </ul>
    </details>
</div>
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="/js/script.js"></script>