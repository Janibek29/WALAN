#include <libmilter/mfapi.h>
#include <iostream>
#include <fstream>
#include <cstring>
#include <mysql/mysql.h>
#include <sstream>
#include <ctime>
#include <iomanip>
#include <vector>

MYSQL *conn;    // Указатель на объект подключения
MYSQL_RES *res; // Результаты запроса
MYSQL_ROW row;  // Строка результата

// Глобальная переменная для пути к лог-файлу
std::string log_path;

// Структура для хранения данных отправителя и получателя
struct EmailData {
    std::string sndr;
    std::string rcpt;
	std::string sbj; // темы
    std::string dt;  // даты
	std::string mid; // Message-ID для уникальной идентификации
};

// Функция для проверки, является ли отправитель локальным
bool is_local_sndr(const char* sender) {
    // Пример проверки — отправитель с домена example.com
    return strstr(sender, "@tirlik.kz") != NULL;
}

// Функция для проверки, является ли получатель локальным
bool is_local_rcpt(const char* recipient) {
    // Пример проверки — получатель с домена example.com
    return strstr(recipient, "@tirlik.kz") != NULL;
}


// Функция для подключения к базе данных
int init_db_connection(const char* host, const char* user, const char* passwd, const char* dbname) {
    conn = mysql_init(NULL);  // Инициализация подключения
    if (conn == NULL) {
        std::cerr << "Ошибка инициализации MySQL: " << mysql_error(conn) << std::endl;
        return 1;  // Ошибка инициализации
    }

    // Подключение к базе данных
    if (mysql_real_connect(conn, host, user, passwd, dbname, 0, NULL, 0) == NULL) {
        std::cerr << "Ошибка подключения: " << mysql_error(conn) << std::endl;
        mysql_close(conn);  // Закрытие соединения при ошибке
        return 1;  // Ошибка подключения
    }

    return 0;  // Подключение успешно
}

// Объявление строки символов Base64 (алфавит Base64)
const std::string base64_chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";

// Функция для проверки, является ли символ допустимым для Base64
bool is_base64(unsigned char c) {
    return (isalnum(c) || (c == '+') || (c == '/'));
}

// Функция декодирования Base64
std::string base64_decode(const std::string& encoded_string) {
    int in_len = encoded_string.size();
    int i = 0;
    int j = 0;
    int in_ = 0;
    std::vector<unsigned char> char_array_4, char_array_3;
    std::string ret;

    while (in_len-- && (encoded_string[in_] != '=') && is_base64(encoded_string[in_])) {
        char_array_4[i++] = encoded_string[in_]; in_++;
        if (i == 4) {
            for (i = 0; i < 4; i++) {
                char_array_4[i] = base64_chars.find(char_array_4[i]);
            }
            char_array_3[0] = (char_array_4[0] << 2) | (char_array_4[1] >> 4);
            char_array_3[1] = (char_array_4[1] << 4) | (char_array_4[2] >> 2);
            char_array_3[2] = (char_array_4[2] << 6) | char_array_4[3];
            for (i = 0; (i < 3); i++) {
                ret += char_array_3[i];
            }
            i = 0;
        }
    }

    if (i) {
        for (j = 0; j < i; j++) {
            char_array_4[j] = base64_chars.find(char_array_4[j]);
        }
        char_array_3[0] = (char_array_4[0] << 2) | (char_array_4[1] >> 4);
        char_array_3[1] = (char_array_4[1] << 4) | (char_array_4[2] >> 2);
        for (j = 0; j < i - 1; j++) {
            ret += char_array_3[j];
        }
    }

    return ret;
}

// Функция обработки подключения
sfsistat ml_connect(SMFICTX *ctx, char *hostname, _SOCK_ADDR *hostaddr) {
    /*std::ofstream log(log_path.c_str(), std::ios::app);
    log << "Connection from: " << hostname << std::endl;
	if (!log) {
        std::cerr << "Failed to write to log file!" << std::endl;
        return SMFIS_TEMPFAIL;
    }	
    log.close();*/
	std::cerr << "ml_connect " << std::endl;
	// Сохраняем адрес отправителя в контексте письма
    EmailData *emailData = (EmailData*)smfi_getpriv(ctx);
    if (emailData == NULL) {
        emailData = new EmailData();
        smfi_setpriv(ctx, emailData);  // Привязка данных к контексту письма
    }
	
    return SMFIS_CONTINUE;
}

// Функция обработки отправителя
sfsistat ml_envfrom(SMFICTX *ctx, char **envfrom) {
	std::cerr << "ml_envfrom " << std::endl;
    EmailData *emailData = (EmailData*)smfi_getpriv(ctx);
    if (emailData != NULL) {
		/*std::string sender(envfrom[0]);
        
        // Убираем символы < и >
        if (!sender.empty() && sender.front() == '<' && sender.back() == '>') {
            sender = sender.substr(1, sender.size() - 2); // Убираем первый и последний символ
        }
        
        emailData->sndr = sender;*/
		emailData->sndr = std::string(envfrom[0]);
	}
	/*
	// Это исходящее письмо, если отправитель с вашего домена
    if (is_local_sndr(envfrom[0])) {
        emailData->isin = false;
        //log << "This is an outgoing email." << std::endl;
    }
	*/
    return SMFIS_CONTINUE;
}

// Функция обработки получателя
sfsistat ml_envrcpt(SMFICTX *ctx, char **envrcpt) {
	std::cerr << "ml_envrcpt " << std::endl;
    // Сохраняем адрес получателя в контексте письма
    EmailData *emailData = (EmailData*)smfi_getpriv(ctx);
    if (emailData != NULL) {
		/*std::string recipient(envrcpt[0]);
        
        // Убираем символы < и >
        if (!recipient.empty() && recipient.front() == '<' && recipient.back() == '>') {
            recipient = recipient.substr(1, recipient.size() - 2); // Убираем первый и последний символ
        }
        
        emailData->rcpt = recipient;*/
        emailData->rcpt = std::string(envrcpt[0]);
		/*
		// Это входящее письмо, если получатель с вашего домена
		if (is_local_rcpt(envrcpt[0])) {
			emailData->isin = true;
			//log << "This is an incoming email." << std::endl;
		}*/
	}
    return SMFIS_CONTINUE;
}

// Функция для обработки заголовков
sfsistat ml_header(SMFICTX *ctx, char *header, char *value) {
	std::cerr << "ml_header " << std::endl;
    // Получаем тему письма
    EmailData *emailData = (EmailData*)smfi_getpriv(ctx);
    if (emailData != NULL) {
        if (strncasecmp(header, "Subject", 7) == 0) {
			/*if (value != NULL && strstr(value, "=?UTF-8?B?") != NULL) {
                // Убираем префикс и суффикс
                char* base64_value = strstr(value, "=?UTF-8?B?") + 10;
                char* end_pos = strstr(base64_value, "?=");
                if (end_pos != NULL) {
                    *end_pos = '\0';  // обрезаем строку на месте
                    emailData->sbj = base64_decode(base64_value);
                }
            } else {
                // Если тема не закодирована, сохраняем как есть
                emailData->sbj = std::string(value);
            }*/
			
			emailData->sbj = std::string(value);
        } else if (strncasecmp(header, "Date", 4) == 0) {
            emailData->dt = std::string(value);
        } else if (strncasecmp(header, "Message-ID", 10) == 0) {
			/*std::string MID(value);
			// Убираем символы < и >
			if (!MID.empty() && MID.front() == '<' && MID.back() == '>') {
				MID = MID.substr(1, MID.size() - 2); // Убираем первый и последний символ
			}
            // Сохраняем Message-ID как уникальный идентификатор
            emailData->mid = MID;  // Добавьте поле msg_id в структуру EmailData
			*/
			emailData->mid = std::string(value);
        }
    }
    return SMFIS_CONTINUE;
}

// Функция обработки конца сообщения
sfsistat ml_eom(SMFICTX *ctx) {
	std::cerr << "ml_eom " << std::endl;
    // Получаем данные о письме из контекста
    EmailData *emailData = (EmailData*)smfi_getpriv(ctx);
    if (emailData == NULL) {
		std::cerr << "emailData = NULL " << std::endl;
        return SMFIS_CONTINUE;  // Если данных нет, продолжаем
    }
	/*
	std::stringstream isIN;
    isIN << emailData->isin;  // Записываем число в строковый поток
    std::string sIN = isIN.str();  // Получаем строку из потока
	*/
	
    
	// Записываем в базу данных
    if (conn) {
        std::string query = "INSERT INTO ems (sndr, rcpt, sbj, dt, mid) VALUES ('" 
                            + emailData->sndr + "', '" + emailData->rcpt + "', '" + emailData->sbj + "', '" + emailData->dt + "', '" + emailData->mid + "')";
        if (mysql_query(conn, query.c_str())) {
            std::cerr << "Ошибка выполнения запроса: " << mysql_error(conn) << std::endl;
        }
    }

    // Освобождаем память, если она была выделена
    delete emailData;
    smfi_setpriv(ctx, NULL);  // Убираем привязанные данные
	
    return SMFIS_CONTINUE;
}

// Основная структура для Milter
/*
struct smfiDesc smfilter = {
    const_cast<char*>("MyMilter"),      // Имя фильтра
    SMFI_VERSION,    // Версия
    SMFIF_NONE,      // Флаги (используем SMFIF_NONE для базовой обработки)
    ml_connect,      // Обработка подключения
    NULL,            // Обработка HELO
    ml_envfrom,      // Обработка отправителя
    ml_envrcpt,      // Обработка получателя
    NULL,       	// Обработка данных (тело письма)
    ml_header,            // Обработка заголовков
    NULL,            // Обработка тела
    ml_eom,          // Конец сообщения
    NULL             // Закрытие соединения
};

struct smfiDesc smfilter = {
    const_cast<char*>("MyMilter"),      // Имя фильтра
    SMFI_VERSION,    // Версия
    SMFIF_NONE,      // Флаги
    ml_connect,      // Обработка подключения
    NULL,            // Обработка HELO
    ml_envfrom,      // Обработка отправителя
    ml_envrcpt,      // Обработка получателя
    ml_header,       // Обработка заголовков
    NULL,            // Обработка тела
    NULL,            // Обработка данных
    ml_eom,          // Конец сообщения
    NULL             // Закрытие соединения
};*/
struct smfiDesc smfilter = {
    const_cast<char*>("MyMilter"),      // Имя фильтра
    SMFI_VERSION,    // Версия
    SMFIF_NONE,      // Флаги (используем SMFIF_NONE для базовой обработки)
    ml_connect,      // 1. Обработка подключения
    NULL,            // 2. Обработка HELO (не требуется)
    ml_envfrom,      // 3. Обработка отправителя
    ml_envrcpt,      // 4. Обработка получателя
    ml_header,            // 5. Обработка тела (не требуется)
    NULL,       // 6. Обработка заголовков (для получения темы и даты)
    NULL,            // 7. Обработка тела письма (не требуется)
    ml_eom,          // 8. Конец сообщения (где можно записывать данные в базу)
    NULL             // 9. Закрытие соединения (не требуется)
};
int main(int argc, char **argv) {
	std::cerr << "main " << std::endl;
	if (argc < 7) {
        std::cerr << "Usage: " << argv[0] << " <host> <user> <password> <dbname> <logfile_path> <socket_path>" << std::endl;
        return 1;
    }

    const char* host = argv[1];     // Первый параметр: host
    const char* user = argv[2];     // Второй параметр: user
    const char* passwd = argv[3];   // Третий параметр: password
    const char* dbname = argv[4];   // Четвертый параметр: dbname
    log_path = argv[5]; // Пятый параметр: путь к файлу лога
    const char* socket_path = argv[6];    // Шестой параметр: путь к сокету
	
	// Инициализация подключения к базе данных с параметрами из командной строки
    if (init_db_connection(host, user, passwd, dbname) != 0) {
        std::cerr << "Ошибка подключения к базе данных" << std::endl;
        return 1;
    }

	
	// Установите путь к сокету
    if (smfi_setconn(const_cast<char*>(socket_path)) != MI_SUCCESS) {
        std::cerr << "Failed to set socket." << std::endl;
        return 1;
    }

    // Установите максимальное количество ожидающих подключений
    if (smfi_setbacklog(5) != MI_SUCCESS) {
        std::cerr << "Failed to set backlog." << std::endl;
        return 1;
    }

    // Зарегистрируйте фильтр
    if (smfi_register(smfilter) != MI_SUCCESS) {
        std::cerr << "Failed to register milter." << std::endl;
        return 1;
    }

    // Запустите фильтр (блокирует выполнение, ожидая подключений)
    if (smfi_main() != MI_SUCCESS) {
        std::cerr << "Milter failed to start." << std::endl;
        return 1;
    }
	
	// Закрытие соединения с базой данных после работы
	if (conn) {
		mysql_close(conn);
	}
	
    return 0;
}
//
///var/www/html/milterWALAN
//echo -n "TEST MESSAGE" | nc -U /var/www/html/my_milter.sock
//cp /var/www/html/my_milter.cpp /var/www/html/my_milter3.cpp
/*
Порядок вызова функций в Milter:

ml_connect – вызывается при установлении подключения. Это место, где можно настроить соединение и привязать любые данные к контексту письма.
ml_envfrom – вызывается при получении адреса отправителя письма (переменная MAIL FROM).
ml_envrcpt – вызывается при получении адреса получателя письма (переменная RCPT TO).
ml_header – вызывается для каждого заголовка письма (например, Subject, From, To, Date и т.д.). Эта функция будет вызываться многократно, один раз для каждого заголовка.
ml_data – вызывается для обработки содержимого тела письма.
ml_eom – вызывается после того, как все данные и заголовки были получены. Это место для выполнения финальной обработки и записи данных, например, в базу данных или лог.
ml_close – вызывается после завершения обработки письма.



g++ -o /var/www/html/milterWALAN /var/www/html/milterWALAN.cpp -lmilter -I/usr/include/mysql -lmysqlclient -std=c++11

vim /etc/systemd/system/milterWALAN.service
[Unit]
Description=Postfix Milter
After=network.target

[Service]
ExecStart=/var/www/html/milterWALAN localhost root MD20241205j walan /var/www/html/milterWALAN.log /var/www/html/milterWALAN.sock
Restart=always
User=postfix
Group=postfix

[Install]
WantedBy=multi-user.target
	*/
