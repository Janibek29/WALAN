#include <libmilter/mfapi.h>
#include <iostream>
#include <fstream>
#include <cstring>
#include <mysql/mysql.h>


	MYSQL *conn;    // Указатель на объект подключения
    MYSQL_RES *res; // Результаты запроса
    MYSQL_ROW row;  // Строка результата
	
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

// Функция обработки подключения
sfsistat ml_connect(SMFICTX *ctx, char *hostname, _SOCK_ADDR *hostaddr) {
    std::ofstream log("/var/www/html/milter.log", std::ios::app);
    log << "Connection from: " << hostname << std::endl;
	if (!log) {
        std::cerr << "Failed to write to log file!" << std::endl;
        return SMFIS_TEMPFAIL;
    }
    log.close();
    return SMFIS_CONTINUE;
}

// Функция обработки отправителя
sfsistat ml_envfrom(SMFICTX *ctx, char **envfrom) {
    std::ofstream log("/var/www/html/milter.log", std::ios::app);
    log << "Mail from: " << envfrom[0] << std::endl;
    log.close();
	
	// Пример использования базы данных (например, запись в базу)
    if (conn) {
        std::string query = "INSERT INTO emails (sender) VALUES ('" + std::string(envfrom[0]) + "')";
        if (mysql_query(conn, query.c_str())) {
            std::cerr << "Ошибка выполнения запроса: " << mysql_error(conn) << std::endl;
        }
    }
	
    return SMFIS_CONTINUE;
}

// Функция обработки получателя
sfsistat ml_envrcpt(SMFICTX *ctx, char **envrcpt) {
    std::ofstream log("/var/www/html/milter.log", std::ios::app);
    log << "Mail to: " << envrcpt[0] << std::endl;
    log.close();
    return SMFIS_CONTINUE;
}

// Функция обработки конца сообщения
sfsistat ml_eom(SMFICTX *ctx) {
    std::ofstream log("/var/www/html/milter.log", std::ios::app);
    log << "End of message." << std::endl;
    log.close();
    return SMFIS_CONTINUE;
}

// Основная структура для Milter
struct smfiDesc smfilter = {
    const_cast<char*>("MyMilter"),      // Имя фильтра
    SMFI_VERSION,    // Версия
    SMFIF_NONE,      // Флаги (используем SMFIF_NONE для базовой обработки)
    ml_connect,      // Обработка подключения
    NULL,            // Обработка HELO
    ml_envfrom,      // Обработка отправителя
    ml_envrcpt,      // Обработка получателя
    NULL,            // Обработка данных (тело письма)
    NULL,            // Обработка заголовков
    NULL,            // Обработка тела
    ml_eom,          // Конец сообщения
    NULL             // Закрытие соединения
};

int main(int argc, char **argv) {
	
	if (argc < 7) {
        std::cerr << "Usage: " << argv[0] << " <host> <user> <password> <dbname> <logfile_path> <socket_path>" << std::endl;
        return 1;
    }

    const char* host = argv[1];     // Первый параметр: host
    const char* user = argv[2];     // Второй параметр: user
    const char* passwd = argv[3];   // Третий параметр: password
    const char* dbname = argv[4];   // Четвертый параметр: dbname
    const std::string log_path = argv[5]; // Пятый параметр: путь к файлу лога
    const char* socket_path = argv[6];    // Шестой параметр: путь к сокету
	
	// Инициализация подключения к базе данных с параметрами из командной строки
    if (init_db_connection(host, user, passwd, dbname) != 0) {
        std::cerr << "Ошибка подключения к базе данных" << std::endl;
        return 1;
    }
	//smfi_setdbgfile("/var/log/milter_debug.log");
	//smfi_setdebug(1);
// Открытие файла для записи
    /*std::ofstream outfile("/var/www/html/milter_debug.log");

    // Проверка, был ли файл открыт успешно
    if (outfile.is_open()) {
        // Запись текста в файл
        outfile << "Hello, world!" << std::endl;
        outfile << "This is a simple text file." << std::endl;

        // Закрытие файла
        outfile.close();
        std::cout << "File created and text written successfully." << std::endl;
    } else {
        std::cerr << "Error opening the file!" << std::endl;
    }*/
	
	


    // Инициализация MySQL
    conn = mysql_init(NULL);
    if (conn == NULL) {
        std::cerr << "Ошибка инициализации MySQL: " << mysql_error(conn) << std::endl;
        return 1;
    }

    // Подключение к базе данных
    const char *host = "localhost";     // Адрес сервера
    const char *user = "root";          // Имя пользователя
    const char *passwd = "MD20241205j"; // Пароль
    const char *dbname = "walan";     // Имя базы данных

    if (mysql_real_connect(conn, host, user, passwd, dbname, 0, NULL, 0) == NULL) {
        std::cerr << "Ошибка подключения: " << mysql_error(conn) << std::endl;
        mysql_close(conn);
        return 1;
    }
/*
    std::cout << "Подключение к базе данных прошло успешно!" << std::endl;

    // Выполнение SQL-запроса
    const char *query = "SELECT * FROM your_table_name"; // Ваш запрос
    if (mysql_query(conn, query)) {
        std::cerr << "Ошибка запроса: " << mysql_error(conn) << std::endl;
        mysql_close(conn);
        return 1;
    }
	
	// Запрос на вставку данных
    const char *query = "INSERT INTO your_table_name (column1, column2) VALUES ('value1', 'value2')";
    if (mysql_query(conn, query)) {
        std::cerr << "Ошибка выполнения запроса: " << mysql_error(conn) << std::endl;
        mysql_close(conn);
        return 1;
    }
	
    // Получение результатов
    res = mysql_store_result(conn);
    if (res == NULL) {
        std::cerr << "Ошибка при получении результата: " << mysql_error(conn) << std::endl;
        mysql_close(conn);
        return 1;
    }

    // Вывод результата
    while ((row = mysql_fetch_row(res))) {
        for (unsigned int i = 0; i < mysql_num_fields(res); i++) {
            std::cout << row[i] << "\t"; // Вывод данных
        }
        std::cout << std::endl;
    }

    // Освобождение результата и закрытие соединения
    mysql_free_result(res);
*/    mysql_close(conn);
	
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
//g++ -o /var/www/html/my_milter /var/www/html/my_milter.cpp -lmilter
//g++ -o /var/www/html/my_milter /var/www/html/my_milter.cpp -lmilter -I/usr/include/mysql -lmysqlclient
///var/www/html/my_milter
//echo -n "TEST MESSAGE" | nc -U /var/www/html/my_milter.sock
//cp /var/www/html/my_milter.cpp /var/www/html/my_milter3.cpp
