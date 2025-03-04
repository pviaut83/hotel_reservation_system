## Diagrama Diagrama de Arquitectura de Software
El diagrama de arquitectura de software muestra los componentes principales del Sistema de Reservación de Habitaciones y sus interacciones. El sistema está diseñado para ser escalable y robusto, permitiendo a los usuarios acceder a la plataforma a través de aplicaciones web y móviles. El backend gestiona funcionalidades clave como autenticación, procesamiento de pagos, administración de inventario y notificaciones, con integración a servicios externos para pagos y sincronización con plataformas de reservas adicionales. La arquitectura modular y distribuida asegura una gestión eficiente de la carga y facilita la escalabilidad, garantizando la disponibilidad y seguridad de los datos en tiempo real.
```
graph TD;
    
    subgraph Cliente
        A[Aplicación Web/Móvil] -->|Solicita información| B[API Gateway]
    end

    subgraph Backend
        B -->|Gestiona peticiones| C[Autenticación y Seguridad]
        B -->|Redirige solicitudes| D[Motor de Búsqueda]
        B -->|Gestiona reservas| E[Gestión de Reservas]
        B -->|Procesa pagos| F[Procesamiento de Pagos]
        B -->|Maneja disponibilidad| G[Gestión de Disponibilidad]
        B -->|Notifica a usuarios| H[Sistema de Notificaciones]
    end

    subgraph Base de Datos
        I[Base de Datos de Usuarios] --> C
        J[Base de Datos de Reservas] --> E
        K[Base de Datos de Disponibilidad] --> G
    end

    subgraph Integraciones Externas
        L[Pasarela de Pago] -->|Valida transacciones| F
        M[Plataformas de Reservas] -->|Sincroniza disponibilidad| G
        N[Servicio de Correos/SMS] -->|Envía confirmaciones| H
    end

    %% Conexiones entre módulos %%
    E -->|Actualiza| K
    G -->|Consulta| J
    F -->|Confirma pago| E
    H -->|Envía notificación| A

```

## Diagrama UML
El diagrama UML representa la arquitectura del sistema de reserva de habitaciones, destacando la interacción entre los distintos componentes organizados en paquetes. Se integran servicios externos como pasarelas de pago y plataformas de reservas, garantizando sincronización y procesamiento seguro de transacciones.

```
classDiagram
    %% Definición de Clases

    class Frontend {
        + Aplicación Web
        + Aplicación Móvil
        + Interfaz de Usuario (UI)
    }

    class LoadBalancer {
        + Distribuye tráfico
        + Balanceo de carga dinámico
    }

    class BackendServices {
        + Gestión de Autenticación
        + Motor de Reservas
        + Procesamiento de Pagos
        + Gestión de Disponibilidad
        + Sistema de Notificaciones
        + Integración con API Externas
    }

    class AuthenticationService {
        + Validación de credenciales
        + Gestión de sesiones (JWT, OAuth)
        + Control de acceso
    }

    class BookingService {
        + Búsqueda de disponibilidad
        + Creación de reservas
        + Modificación y cancelación
    }

    class PaymentService {
        + Procesamiento de pagos seguros
        + Integración con pasarelas de pago
        + Gestión de transacciones
    }

    class InventoryService {
        + Administración de disponibilidad
        + Sincronización en tiempo real
    }

    class NotificationService {
        + Envío de notificaciones (Email/SMS/Push)
        + Confirmación de reservas
    }

    class Database {
        + Usuarios
        + Reservas
        + Pagos
        + Inventario de Habitaciones
    }

    class ExternalServices {
        + Pasarela de Pago (Stripe, PayPal)
        + Plataformas de Reservas (Booking.com, Expedia)
        + Servicios de Notificación (Email/SMS)
    }

    %% Relaciones
    Frontend --> LoadBalancer : "Envío de solicitudes"
    LoadBalancer --> BackendServices : "Distribuye carga"
    BackendServices --> AuthenticationService : "Manejo de usuarios"
    BackendServices --> BookingService : "Gestión de reservas"
    BackendServices --> PaymentService : "Procesamiento de pagos"
    BackendServices --> InventoryService : "Administración de habitaciones"
    BackendServices --> NotificationService : "Manejo de notificaciones"
    BackendServices --> Database : "CRUD de datos"
    BackendServices --> ExternalServices : "Integraciones externas"

    AuthenticationService --> Database : "Gestión de credenciales"
    BookingService --> Database : "Almacena reservas"
    PaymentService --> ExternalServices : "Solicita pago"
    InventoryService --> Database : "Sincroniza disponibilidad"
    NotificationService --> ExternalServices : "Envío de notificaciones"
```

## Diagrama de Secuencia UML
El diagrama de secuencia UML detalla el flujo de interacciones en el sistema durante el proceso de reservación de una habitación. 

```
sequenceDiagram
    participant Usuario
    participant Frontend as Aplicación Web/Móvil
    participant LoadBalancer as Load Balancer
    participant AuthService as Servicio de Autenticación
    participant BookingService as Servicio de Reservas
    participant InventoryService as Servicio de Inventario
    participant PaymentService as Servicio de Pagos
    participant PaymentGateway as Pasarela de Pago
    participant NotificationService as Servicio de Notificaciones
    participant ExternalAPI as Integrador de Plataformas Externas
    participant EmailService as Servicio de Email/SMS
    participant DB as Base de Datos

    Usuario ->> Frontend: 1. Ingresar fechas y buscar disponibilidad
    Frontend ->> LoadBalancer: 2. Enviar solicitud de autenticación
    LoadBalancer ->> AuthService: 3. Validar credenciales del usuario
    AuthService -->> LoadBalancer: 4. Confirmación de autenticación
    LoadBalancer -->> Frontend: 5. Usuario autenticado
    
    Frontend ->> LoadBalancer: 6. Solicitar disponibilidad de habitaciones
    LoadBalancer ->> BookingService: 7. Redirigir solicitud de búsqueda
    BookingService ->> InventoryService: 8. Consultar disponibilidad
    InventoryService ->> DB: 9. Verificar disponibilidad en la base de datos
    DB -->> InventoryService: 10. Retornar habitaciones disponibles
    InventoryService -->> BookingService: 11. Enviar disponibilidad
    BookingService -->> LoadBalancer: 12. Enviar resultados de búsqueda
    LoadBalancer -->> Frontend: 13. Mostrar opciones de habitaciones

    Usuario ->> Frontend: 14. Seleccionar habitación y proceder a reservar
    Frontend ->> LoadBalancer: 15. Enviar solicitud de reserva
    LoadBalancer ->> BookingService: 16. Validar disponibilidad y bloquear habitación temporalmente
    BookingService ->> InventoryService: 17. Bloquear habitación en el inventario
    InventoryService -->> BookingService: 18. Confirmar bloqueo temporal

    BookingService ->> LoadBalancer: 19. Redirigir autenticación del usuario
    LoadBalancer ->> AuthService: 20. Verificar autenticación
    AuthService -->> LoadBalancer: 21. Confirmar autenticación
    LoadBalancer -->> BookingService: 22. Autenticación exitosa

    Usuario ->> Frontend: 23. Confirmar detalles y proceder al pago
    Frontend ->> LoadBalancer: 24. Enviar datos de pago
    LoadBalancer ->> PaymentService: 25. Procesar pago
    PaymentService ->> PaymentGateway: 26. Procesar transacción
    PaymentGateway -->> PaymentService: 27. Confirmar transacción exitosa
    PaymentService ->> DB: 28. Registrar transacción en la base de datos

    PaymentService -->> BookingService: 29. Confirmación de pago recibido
    BookingService ->> InventoryService: 30. Confirmar reserva y actualizar inventario
    BookingService ->> DB: 31. Registrar reserva en la base de datos

    BookingService ->> NotificationService: 32. Generar confirmación de reserva
    NotificationService ->> EmailService: 33. Enviar email/SMS de confirmación
    NotificationService -->> Usuario: 34. Confirmación de reserva recibida

    BookingService ->> ExternalAPI: 35. Sincronizar disponibilidad con plataformas externas
    ExternalAPI -->> ExternalAPI: 36. Actualizar inventario en plataformas como Booking.com
```
## Diagrama de Estados
Este diagrama de transición de estados ilustra el flujo del sistema durante el proceso de reserva de una habitación, desde la búsqueda inicial hasta la confirmación y sincronización con plataformas externas. El sistema atraviesa distintos estados clave, asegurando disponibilidad en tiempo real, autenticación del usuario, procesamiento seguro de pagos y actualización del inventario. Inicialmente, el usuario busca habitaciones disponibles, lo que activa una verificación de disponibilidad para bloquear temporalmente la habitación y evitar sobre-reservas. Si la habitación está disponible, el usuario procede a la autenticación antes de confirmar la reserva. Una vez autenticado, la reserva pasa a estado "Pendiente" hasta que se realice el pago. Si el pago se procesa correctamente, la reserva cambia a estado "Pagada", activando la actualización del inventario y la sincronización con plataformas externas. En caso de error en el pago, el usuario puede reintentar la transacción. Además, se permite modificar una reserva antes del pago o cancelarla en cualquier estado. Finalmente, cuando la reserva ha sido confirmada y sincronizada, se notifica al usuario y la transacción se cierra. Este flujo garantiza una experiencia fluida y confiable, optimizando la gestión de reservas en tiempo real.

```
stateDiagram
    [*] --> InicioBusqueda
    InicioBusqueda --> HabitacionesDisponibles: Buscar Habitaciones
    HabitacionesDisponibles --> SeleccionHabitacion: Usuario selecciona una habitación
    SeleccionHabitacion --> VerificacionDisponibilidad: Verificar y bloquear disponibilidad
    VerificacionDisponibilidad --> AutenticacionUsuario: Habitación disponible
    VerificacionDisponibilidad --> HabitacionesDisponibles: Habitación no disponible
    AutenticacionUsuario --> ConfirmacionDatosReserva: Usuario autenticado
    AutenticacionUsuario --> [*]: Fallo en autenticación / Cancelar
    
    ConfirmacionDatosReserva --> ProcesamientoPago: Confirmar detalles y proceder al pago
    ProcesamientoPago --> ReservaConfirmada: Pago exitoso
    ProcesamientoPago --> ConfirmacionDatosReserva: Pago fallido / Reintentar
    ConfirmacionDatosReserva --> Cancelada: Usuario cancela antes del pago
    
    ReservaConfirmada --> EnvioNotificacion: Confirmar reserva y actualizar inventario
    EnvioNotificacion --> SincronizacionExterna: Enviar confirmación al usuario
    SincronizacionExterna --> [*]: Actualizar disponibilidad en plataformas externas
    
    ReservaConfirmada --> Modificada: Usuario modifica reserva
    Modificada --> EnvioNotificacion: Generar nueva confirmación
    
    ReservaConfirmada --> Cancelada: Usuario cancela después de pago
    Cancelada --> [*]: Reserva cancelada
```

## Estructura del proyecto
La estructura de carpetas del sistema de reserva de habitaciones ha sido diseñada para optimizar el desarrollo, facilitar el mantenimiento y garantizar la escalabilidad del proyecto. Se organiza en módulos bien definidos que separan los principales componentes de la aplicación. El frontend alberga las aplicaciones web y móviles, organizadas para ofrecer una experiencia de usuario fluida mediante la gestión de componentes reutilizables, servicios de comunicación con la API y un manejo eficiente del estado global. El backend sigue una arquitectura basada en microservicios, con módulos independientes para autenticación, gestión de reservas, procesamiento de pagos e inventario, permitiendo una escalabilidad eficiente y mantenibilidad modular. Además, el sistema incorpora un módulo de integración que gestiona la conexión con pasarelas de pago, APIs externas y servicios de mensajería, asegurando la interoperabilidad con otras plataformas. Para mejorar la reutilización de código y la eficiencia en el desarrollo, se incluye un directorio shared, donde se centralizan utilidades, middlewares y modelos de datos compartidos entre servicios. Finalmente, el directorio deployment consolida la configuración para la contenedorización y automatización de despliegues mediante Docker, Kubernetes y CI/CD, optimizando la gestión del ciclo de vida del sistema. Esta organización modular no solo mejora la estructura y mantenibilidad del código, sino que también permite la incorporación ágil de nuevas funcionalidades sin comprometer la estabilidad ni el rendimiento de la plataforma.

```tree

/hotel-reservation-system
│
├── frontend/
│   ├── web-app/
│   │   ├── public/                 # Archivos públicos como index.html, favicon, etc.
│   │   ├── src/
│   │   │   ├── assets/             # Imágenes, fuentes, estilos globales
│   │   │   ├── components/         # Componentes reutilizables de UI
│   │   │   ├── pages/              # Páginas principales del sitio web
│   │   │   ├── services/           # Lógica de comunicación con la API backend
│   │   │   ├── store/              # Manejo del estado global (Redux, Vuex, Zustand)
│   │   │   ├── utils/              # Funciones utilitarias
│   │   │   └── App.js              # Componente principal de la aplicación
│   │   └── package.json            # Dependencias y scripts de la aplicación web
│   │
│   └── mobile-app/
│       ├── assets/                 # Imágenes y recursos específicos para móviles
│       ├── src/
│       │   ├── components/         # Componentes reutilizables de UI para móviles
│       │   ├── screens/            # Pantallas principales de la app móvil
│       │   ├── services/           # Lógica de comunicación con la API backend
│       │   ├── store/              # Manejo del estado global
│       │   ├── utils/              # Funciones utilitarias
│       │   └── App.js              # Componente principal de la app móvil
│       └── package.json            # Dependencias y scripts de la app móvil
│
├── backend/
│   ├── auth-service/               # Servicio de autenticación
│   │   ├── src/
│   │   │   ├── controllers/        # Controladores para manejar solicitudes HTTP
│   │   │   ├── models/             # Modelos de la base de datos
│   │   │   ├── routes/             # Definición de rutas y middlewares
│   │   │   ├── services/           # Lógica de autenticación
│   │   │   ├── utils/              # Funciones utilitarias
│   │   │   └── index.js            # Punto de entrada del servicio
│   │   └── package.json
│   │
│   ├── booking-service/            # Servicio de reservas
│   │   ├── src/
│   │   │   ├── controllers/
│   │   │   ├── models/
│   │   │   ├── routes/
│   │   │   ├── services/
│   │   │   ├── utils/
│   │   │   └── index.js
│   │   └── package.json
│   │
│   ├── inventory-service/          # Servicio de disponibilidad e inventario
│   │   ├── src/
│   │   │   ├── controllers/
│   │   │   ├── models/
│   │   │   ├── routes/
│   │   │   ├── services/
│   │   │   ├── utils/
│   │   │   └── index.js
│   │   └── package.json
│   │
│   ├── payment-service/            # Servicio de pagos
│   │   ├── src/
│   │   │   ├── controllers/
│   │   │   ├── models/
│   │   │   ├── routes/
│   │   │   ├── services/
│   │   │   ├── utils/
│   │   │   └── index.js
│   │   └── package.json
│   │
│   └── notification-service/       # Servicio de notificaciones
│       ├── src/
│       │   ├── controllers/
│       │   ├── models/
│       │   ├── routes/
│       │   ├── services/
│       │   ├── utils/
│       │   └── index.js
│       └── package.json
│
├── integration/
│   ├── external-api/               # Integración con APIs externas (Booking, Expedia)
│   │   ├── src/
│   │   │   ├── clients/            # Clientes para comunicación con APIs externas
│   │   │   ├── services/           # Lógica de integración y sincronización
│   │   │   ├── utils/
│   │   │   └── index.js
│   │   └── package.json
│   │
│   ├── payment-gateways/           # Integración con Stripe, PayPal, MercadoPago
│   │   ├── stripe/
│   │   │   ├── src/
│   │   │   │   └── client.js       # Cliente para API de Stripe
│   │   │   └── package.json
│   │   └── paypal/
│   │       ├── src/
│   │       │   └── client.js       # Cliente para API de PayPal
│   │       └── package.json
│   │
│   └── messaging/                  # Servicios de notificaciones y mensajería
│       ├── email/
│       │   ├── src/
│       │   │   └── emailClient.js  # Cliente para correos electrónicos
│       │   └── package.json
│       └── sms/
│           ├── src/
│           │   └── smsClient.js    # Cliente para SMS
│           └── package.json
│
├── shared/
│   ├── configs/                    # Configuraciones compartidas (archivos de entorno)
│   ├── constants/                  # Definiciones de constantes globales
│   ├── middleware/                 # Middlewares compartidos (autenticación, logs)
│   ├── utils/                      # Utilidades comunes (logger, formateadores)
│   ├── models/                     # Modelos de datos compartidos entre microservicios
│   ├── docs/                       # Documentación general del proyecto
│
└── deployment/
    ├── docker/                     # Configuración de contenedores Docker
    ├── kubernetes/                  # Configuración de Kubernetes
    ├── ci-cd/                        # Pipelines de CI/CD (GitHub Actions, GitLab)
    ├── terraform/                    # Infraestructura como código (AWS, GCP, Azure)
    ├── scripts/                      # Scripts de automatización de despliegues

```
