#include <libmilter/mfapi.h>
#include <iostream>
#include <fstream>
#include <cstring>

#define SOCKET_PATH "/var/www/html/my_milter.sock"
//#define SMFIF_ACCEPT 0x01
// Функция обработки подключения
sfsistat ml_connect(SMFICTX *ctx, char *hostname, _SOCK_ADDR *hostaddr) {
    std::ofstream log("/var/log/milter.log", std::ios::app);
    log << "Connection from: " << hostname << std::endl;
    log.close();
    return SMFIS_CONTINUE;
}

// Функция обработки отправителя
sfsistat ml_envfrom(SMFICTX *ctx, char **envfrom) {
    std::ofstream log("/var/log/milter.log", std::ios::app);
    log << "Mail from: " << envfrom[0] << std::endl;
    log.close();
    return SMFIS_CONTINUE;
}

// Функция обработки получателя
sfsistat ml_envrcpt(SMFICTX *ctx, char **envrcpt) {
    std::ofstream log("/var/log/milter.log", std::ios::app);
    log << "Mail to: " << envrcpt[0] << std::endl;
    log.close();
    return SMFIS_CONTINUE;
}

// Функция обработки конца сообщения
sfsistat ml_eom(SMFICTX *ctx) {
    std::ofstream log("/var/log/milter.log", std::ios::app);
    log << "End of message." << std::endl;
    log.close();
    return SMFIS_CONTINUE;
}

// Основная структура для Milter
struct smfiDesc smfilter = {
    const_cast<char*>("MyMilter"),      // Имя фильтра
    SMFI_VERSION,    // Версия
    SMFIF_NONE,      // Флаги SMFIF_NONE SMFIF_ACCEPT
    ml_connect,      // Обработка подключения
    NULL,            // Обработка HELO
    ml_envfrom,      // Обработка отправителя
    ml_envrcpt,      // Обработка получателя
    NULL,            // Обработка данных
    NULL,            // Обработка заголовков
    NULL,            // Обработка тела
    ml_eom,          // Конец сообщения
    NULL             // Закрытие соединения
};

int main(int argc, char **argv) {
    // Установите путь к сокету
    if (smfi_setconn(const_cast<char*>(SOCKET_PATH)) != MI_SUCCESS) {
        std::cerr << "Failed to set socket." << std::endl;
        return 1;
    }

    // Установите права доступа
    if (smfi_setbacklog(5) != MI_SUCCESS) {
        std::cerr << "Failed to set backlog." << std::endl;
        return 1;
    }

    // Зарегистрируйте фильтр
    if (smfi_register(smfilter) != MI_SUCCESS) {
        std::cerr << "Failed to register milter." << std::endl;
        return 1;
    }

    // Запустите фильтр
    if (smfi_main() != MI_SUCCESS) {
        std::cerr << "Milter failed to start." << std::endl;
        return 1;
    }

    return 0;
}
//g++ -o /var/www/html/my_milter /var/www/html/my_milter.cpp -lmilter
///var/www/html/my_milter
//echo -n "TEST MESSAGE" | nc -U /var/www/html/my_milter.sock