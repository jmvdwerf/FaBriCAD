# Name: Makefile
# Author:
# - Jan Martijn van der Werf <janmartijn@slashdev.nl>
# Based on Makefile by
# - Ferdi van der Werf
#######################################
-include $(wildcard Makefile.make)

#######################################
BINARY       ?= fabricad
#######################################
BUILD_DIR    ?= build
SOURCES_DIR  ?= src

OPTIMIZATION ?= s
DEBUG_LEVEL  ?= 3
#######################################
.PHONY: all clear rebuild watch help clean reset directories


# Objects dir
OBJECTS_DIR = $(BUILD_DIR)/obj

INCLUDES   += -Iinclude
INCLUDES   += -Isrc

SOURCES     = $(wildcard $(SOURCES_DIR)/**/*.cc) $(wildcard $(SOURCES_DIR)/*.cc)
OBJECTS     = $(addprefix $(OBJECTS_DIR)/, $(notdir %/$(subst .cc,.o, $(SOURCES))))
DIRECTORIES = $(patsubst $(SOURCES_DIR)/%,$(OBJECTS_DIR)/%,$(sort $(dir $(wildcard $(SOURCES_DIR)/*/))))

CC 					= g++
OBJCOPY     = objcopy
#######################################


CC_FLAGS   += $(INCLUDES)
# CC_FLAGS   += -std=c++17
# Required for Boost::fileystem
LD_FLAGS   += -lboost_filesystem -lboost_system

# CC_FLAGS   += $(DEFINES)

COL_INFO    = tput setaf 2
COL_BUILD   = tput setaf 7
COL_ERROR   = tput setaf 1
COL_RESET   = tput sgr0

BIN         = $(BUILD_DIR)/$(BINARY)

#######################################
log_info = $(COL_RESET); printf "$(1) "
log_ok   = $(COL_RESET); printf "["; $(COL_INFO); printf "OK"; $(COL_RESET); printf "]\n"



rebuild: clear clean all

all: directories $(BIN)

clear:
	@clear


watch:
	@clear
	@echo "Watching current directory for changes"
	@fswatch --recursive --event Updated --exclude build --one-per-batch ./include/ ./src/  | xargs -n1 -I{} make rebuild

# Clean environment
clean:
	@$(call log_info,"Cleaning...")
	@rm -rf $(BIN) $(OBJECTS_DIR)
	@$(call log_ok)

directories:
	@$(call log_info,Create directories in \'$(OBJECTS_DIR)\')
	@mkdir -p $(DIRECTORIES) $(BUILD_DIR)
	@$(call log_ok)

%.o:
	$(info $@)
	@$(call log_info,Compiling $(filter %/$(subst .o,.cc,$(notdir $@)), $(SOURCES)))
	@$(COL_ERROR)
	@$(CC) $(CC_FLAGS) $(filter %/$(subst .o,.cc,$(notdir $@)), $(SOURCES)) -c -o $@
	@$(call log_ok)

$(BIN): ${OBJECTS}
	@echo
	@$(call log_info,Building $(BIN))
	@$(COL_ERROR)
	@$(CC) $(LD_FLAGS) $(OBJECTS) $(LIBS) -o $(BIN)
	@$(call log_ok)
