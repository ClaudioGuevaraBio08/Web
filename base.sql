--
-- PostgreSQL database dump
--

-- Dumped from database version 12.2 (Ubuntu 12.2-4)
-- Dumped by pg_dump version 12.2 (Ubuntu 12.2-4)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: actividad; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.actividad (
    id_actividad integer NOT NULL,
    id_dificultad integer NOT NULL,
    correo character varying(1024) NOT NULL,
    enunciado character varying(1024) NOT NULL,
    nombre character varying(1024)
);


ALTER TABLE public.actividad OWNER TO postgres;

--
-- Name: actividad_id_actividad_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.actividad_id_actividad_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.actividad_id_actividad_seq OWNER TO postgres;

--
-- Name: actividad_id_actividad_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.actividad_id_actividad_seq OWNED BY public.actividad.id_actividad;


--
-- Name: dificultad; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.dificultad (
    id_dificultad integer NOT NULL,
    nombre character varying(1024) NOT NULL
);


ALTER TABLE public.dificultad OWNER TO postgres;

--
-- Name: dificultad_id_dificultad_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.dificultad_id_dificultad_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.dificultad_id_dificultad_seq OWNER TO postgres;

--
-- Name: dificultad_id_dificultad_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.dificultad_id_dificultad_seq OWNED BY public.dificultad.id_dificultad;


--
-- Name: lenguaje; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.lenguaje (
    id_lenguaje integer NOT NULL,
    nombre character varying(1024) NOT NULL
);


ALTER TABLE public.lenguaje OWNER TO postgres;

--
-- Name: lenguaje_id_lenguaje_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.lenguaje_id_lenguaje_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.lenguaje_id_lenguaje_seq OWNER TO postgres;

--
-- Name: lenguaje_id_lenguaje_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.lenguaje_id_lenguaje_seq OWNED BY public.lenguaje.id_lenguaje;


--
-- Name: solucion; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.solucion (
    id_lenguaje integer NOT NULL,
    id_actividad integer NOT NULL,
    solucion character varying(1024) NOT NULL
);


ALTER TABLE public.solucion OWNER TO postgres;

--
-- Name: tipo_usuario; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tipo_usuario (
    id_tipo integer NOT NULL,
    nombre_tipo character varying(1024) NOT NULL
);


ALTER TABLE public.tipo_usuario OWNER TO postgres;

--
-- Name: tipo_usuario_id_tipo_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tipo_usuario_id_tipo_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tipo_usuario_id_tipo_seq OWNER TO postgres;

--
-- Name: tipo_usuario_id_tipo_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tipo_usuario_id_tipo_seq OWNED BY public.tipo_usuario.id_tipo;


--
-- Name: tutorial; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tutorial (
    id_tutorial integer NOT NULL,
    id_lenguaje integer NOT NULL,
    nombre_tutorial character varying(1024) NOT NULL,
    instrucciones character varying(1024) NOT NULL,
    link_video character varying(1024)
);


ALTER TABLE public.tutorial OWNER TO postgres;

--
-- Name: tutorial_id_tutorial_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tutorial_id_tutorial_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tutorial_id_tutorial_seq OWNER TO postgres;

--
-- Name: tutorial_id_tutorial_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tutorial_id_tutorial_seq OWNED BY public.tutorial.id_tutorial;


--
-- Name: usuario; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.usuario (
    correo character varying(1024) NOT NULL,
    id_tipo integer NOT NULL,
    contrasena character varying(1024) NOT NULL,
    nombre character varying(1024) NOT NULL,
    apellido character varying(1024) NOT NULL
);


ALTER TABLE public.usuario OWNER TO postgres;

--
-- Name: actividad id_actividad; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.actividad ALTER COLUMN id_actividad SET DEFAULT nextval('public.actividad_id_actividad_seq'::regclass);


--
-- Name: dificultad id_dificultad; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dificultad ALTER COLUMN id_dificultad SET DEFAULT nextval('public.dificultad_id_dificultad_seq'::regclass);


--
-- Name: lenguaje id_lenguaje; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lenguaje ALTER COLUMN id_lenguaje SET DEFAULT nextval('public.lenguaje_id_lenguaje_seq'::regclass);


--
-- Name: tipo_usuario id_tipo; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_usuario ALTER COLUMN id_tipo SET DEFAULT nextval('public.tipo_usuario_id_tipo_seq'::regclass);


--
-- Name: tutorial id_tutorial; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tutorial ALTER COLUMN id_tutorial SET DEFAULT nextval('public.tutorial_id_tutorial_seq'::regclass);


--
-- Data for Name: actividad; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.actividad (id_actividad, id_dificultad, correo, enunciado, nombre) FROM stdin;
\.


--
-- Data for Name: dificultad; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.dificultad (id_dificultad, nombre) FROM stdin;
1	Facil
2	Medio
3	Dificil
4	Muy Dificil
\.


--
-- Data for Name: lenguaje; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.lenguaje (id_lenguaje, nombre) FROM stdin;
1	C
2	C++
3	Python
4	Java
\.


--
-- Data for Name: solucion; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.solucion (id_lenguaje, id_actividad, solucion) FROM stdin;
\.


--
-- Data for Name: tipo_usuario; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tipo_usuario (id_tipo, nombre_tipo) FROM stdin;
1	Administrador
2	Alumno
\.


--
-- Data for Name: tutorial; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tutorial (id_tutorial, id_lenguaje, nombre_tutorial, instrucciones, link_video) FROM stdin;
\.


--
-- Data for Name: usuario; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.usuario (correo, id_tipo, contrasena, nombre, apellido) FROM stdin;
usu1@admin.com	1	cU8rVzh2L29JVHR1Z21hMThMR3pMUT09	Usuario	Admin
usu2@admin.com	1	cU8rVzh2L29JVHR1Z21hMThMR3pMUT09	Usuario	Admin
usu3@admin.com	1	cU8rVzh2L29JVHR1Z21hMThMR3pMUT09	Usuario	Admin
usu4@admin.com	1	cU8rVzh2L29JVHR1Z21hMThMR3pMUT09	Usuario	Admin
usu5@admin.com	1	cU8rVzh2L29JVHR1Z21hMThMR3pMUT09	Usuario	Admin
gabriel@gmail.com	2	cU8rVzh2L29JVHR1Z21hMThMR3pMUT09	Gabriel	Cabas
claudio@gmail.com	2	cU8rVzh2L29JVHR1Z21hMThMR3pMUT09	Claudio	Guevara
\.


--
-- Name: actividad_id_actividad_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.actividad_id_actividad_seq', 1, false);


--
-- Name: dificultad_id_dificultad_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.dificultad_id_dificultad_seq', 4, true);


--
-- Name: lenguaje_id_lenguaje_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.lenguaje_id_lenguaje_seq', 4, true);


--
-- Name: tipo_usuario_id_tipo_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tipo_usuario_id_tipo_seq', 2, true);


--
-- Name: tutorial_id_tutorial_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tutorial_id_tutorial_seq', 1, false);


--
-- Name: actividad pk_actividad; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.actividad
    ADD CONSTRAINT pk_actividad PRIMARY KEY (id_actividad);


--
-- Name: dificultad pk_dificultad; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dificultad
    ADD CONSTRAINT pk_dificultad PRIMARY KEY (id_dificultad);


--
-- Name: lenguaje pk_lenguaje; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lenguaje
    ADD CONSTRAINT pk_lenguaje PRIMARY KEY (id_lenguaje);


--
-- Name: solucion pk_solucion; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.solucion
    ADD CONSTRAINT pk_solucion PRIMARY KEY (id_lenguaje, id_actividad);


--
-- Name: tipo_usuario pk_tipo_usuario; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_usuario
    ADD CONSTRAINT pk_tipo_usuario PRIMARY KEY (id_tipo);


--
-- Name: tutorial pk_tutorial; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tutorial
    ADD CONSTRAINT pk_tutorial PRIMARY KEY (id_tutorial);


--
-- Name: usuario pk_usuario; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuario
    ADD CONSTRAINT pk_usuario PRIMARY KEY (correo);


--
-- Name: actividad_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX actividad_pk ON public.actividad USING btree (id_actividad);


--
-- Name: crear_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX crear_fk ON public.actividad USING btree (correo);


--
-- Name: dificultad_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX dificultad_pk ON public.dificultad USING btree (id_dificultad);


--
-- Name: lenguaje_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX lenguaje_pk ON public.lenguaje USING btree (id_lenguaje);


--
-- Name: pertenece_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX pertenece_fk ON public.usuario USING btree (id_tipo);


--
-- Name: posee_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX posee_fk ON public.actividad USING btree (id_dificultad);


--
-- Name: solucion2_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX solucion2_fk ON public.solucion USING btree (id_actividad);


--
-- Name: solucion_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX solucion_fk ON public.solucion USING btree (id_lenguaje);


--
-- Name: solucion_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX solucion_pk ON public.solucion USING btree (id_lenguaje, id_actividad);


--
-- Name: tiene_fk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX tiene_fk ON public.tutorial USING btree (id_lenguaje);


--
-- Name: tipo_usuario_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX tipo_usuario_pk ON public.tipo_usuario USING btree (id_tipo);


--
-- Name: tutorial_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX tutorial_pk ON public.tutorial USING btree (id_tutorial);


--
-- Name: usuario_pk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX usuario_pk ON public.usuario USING btree (correo);


--
-- Name: actividad fk_activida_crear_usuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.actividad
    ADD CONSTRAINT fk_activida_crear_usuario FOREIGN KEY (correo) REFERENCES public.usuario(correo) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: actividad fk_activida_posee_dificult; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.actividad
    ADD CONSTRAINT fk_activida_posee_dificult FOREIGN KEY (id_dificultad) REFERENCES public.dificultad(id_dificultad) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: solucion fk_solucion_solucion2_activida; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.solucion
    ADD CONSTRAINT fk_solucion_solucion2_activida FOREIGN KEY (id_actividad) REFERENCES public.actividad(id_actividad) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: solucion fk_solucion_solucion_lenguaje; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.solucion
    ADD CONSTRAINT fk_solucion_solucion_lenguaje FOREIGN KEY (id_lenguaje) REFERENCES public.lenguaje(id_lenguaje) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: tutorial fk_tutorial_tiene_lenguaje; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tutorial
    ADD CONSTRAINT fk_tutorial_tiene_lenguaje FOREIGN KEY (id_lenguaje) REFERENCES public.lenguaje(id_lenguaje) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: usuario fk_usuario_pertenece_tipo_usu; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuario
    ADD CONSTRAINT fk_usuario_pertenece_tipo_usu FOREIGN KEY (id_tipo) REFERENCES public.tipo_usuario(id_tipo) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- PostgreSQL database dump complete
--

