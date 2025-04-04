#include <libmilter/mfapi.h>
#include <iostream>
#include <fstream>
#include <cstring>
#include <mysql/mysql.h>

MYSQL *conn = NULL;  // Указатель на объект подключения

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
sfsistat ml_connect(SMFICTX *ctx, char *hostname, _SOCK_ADDR *hostaddr, const std::string &log_path) {
    std::ofstream log(log_path, std::ios::app);  // Используем путь к лог-файлу из параметров
    log << "Connection from: " << hostname << std::endl;
    if (!log) {
        std::cerr << "Failed to write to log file!" << std::endl;
        return SMFIS_TEMPFAIL;
    }
    log.close();
    return SMFIS_CONTINUE;
}

sfsistat ml_connect(SMFICTX *ctx, char *hostname, _SOCK_ADDR *hostaddr) {
    // Получаем IP-адрес
    char ip[INET_ADDRSTRLEN];
    inet_ntop(AF_INET, &hostaddr->sa.sa_in.sin_addr, ip, sizeof(ip));

    std::ofstream log("/var/www/html/milter.log", std::ios::app);
    log << "Connection from IP: " << ip << std::endl;

    // Пример сравнения IP-адреса для входящего/исходящего
    if (strcmp(ip, "your.server.ip.address") == 0) {
        log << "This is an outgoing message." << std::endl;
    } else {
        log << "This is an incoming message." << std::endl;
    }

    log.close();
    return SMFIS_CONTINUE;
}


// Функция обработки отправителя
sfsistat ml_envfrom(SMFICTX *ctx, char **envfrom, const std::string &log_path) {
    std::ofstream log(log_path, std::ios::app);  // Используем путь к лог-файлу из параметров
    log << "Mail from: " << envfrom[0] << std::endl;

    // Пример использования базы данных (например, запись в базу)
    if (conn) {
        std::string query = "INSERT INTO emails (sender) VALUES ('" + std::string(envfrom[0]) + "')";
        if (mysql_query(conn, query.c_str())) {
            std::cerr << "Ошибка выполнения запроса: " << mysql_error(conn) << std::endl;
        }
    }

    log.close();
    return SMFIS_CONTINUE;
}

// Функция обработки получателя
sfsistat ml_envrcpt(SMFICTX *ctx, char **envrcpt) {
    std::ofstream log("/var/www/html/milter.log", std::ios::app);
    log << "Mail to: " << envrcpt[0] << std::endl;
    log.close();
    return SMFIS_CONTINUE;
}

// Основная функция
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
//cp /var/www/html/my_milter.cpp /var/www/html/my_milter2.cpp
